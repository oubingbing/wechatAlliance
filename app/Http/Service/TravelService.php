<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13 0013
 * Time: 15:11
 */

namespace App\Http\Service;


use App\Exceptions\ApiException;
use App\Models\TravelLog;
use App\Models\TravelLogPoi;
use App\Models\TravelPlan;
use App\Models\TravelPlanPoint;
use Carbon\Carbon;

class TravelService
{
    public function saveTravelPlan($userId,$title,$distance)
    {
        $plan = TravelPlan::create([
            TravelPlan::FIELD_ID_USER=>$userId,
            TravelPlan::FIELD_TITLE=>empty($title)?'无':$title,
            TravelPlan::FIELD_DISTANCE=>$distance,
            TravelPlan::FIELD_STATUS=>TravelPlan::ENUM_STATUS_TRAVeLING
        ]);

        return $plan;
    }

    /**
     * 保存旅游站点
     *
     * @author yezi
     *
     * @param $planId
     * @param $points
     * @return array
     */
    public function saveTravelPlanPoint($planId,$points)
    {
        $planArray = [];
        $length = count($points);
        foreach ($points as $key => $point){
            if($key == 0){
                $type = TravelPlanPoint::ENUM_TYPE_START_POINT;
            }elseif($key == ($length - 1)){
                $type = TravelPlanPoint::ENUM_TYPE_END_POINT;
            }else{
                $type = TravelPlanPoint::ENUM_TYPE_ROUTE_POINT;
            }
            array_push($planArray,[
                TravelPlanPoint::FIELD_ID_TRAVEL_PLAN=>$planId,
                TravelPlanPoint::FIELD_NAME=>$point['name'],
                TravelPlanPoint::FIELD_ADDRESS=>$point['address'],
                TravelPlanPoint::FIELD_LATITUDE=>$point['latitude'],
                TravelPlanPoint::FIELD_LONGITUDE=>$point['longitude'],
                TravelPlanPoint::FIELD_SORT=>$point['id'],
                TravelPlanPoint::FIELD_TYPE=>$type,
                TravelPlanPoint::FIELD_CREATED_AT=>Carbon::now(),
                TravelPlanPoint::FIELD_UPDATED_AT=>Carbon::now()
            ]);
        }

        if($planArray){
            TravelPlanPoint::insert($planArray);
        }

        return $planArray;
    }

    /**
     * 用户进行中旅行计划
     *
     * @author yezi
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function travelingPlan($userId)
    {
        $plans = TravelPlan::query()
            ->with([
                TravelPlan::REL_POINTS,
                TravelPlan::REL_TRAVEL_LOGS=>function($query){
                    $query->select([
                        TravelLog::FIELD_ID,
                        TravelLog::FIELD_ID_TRAVEL_PLAN,
                        TravelLog::FIELD_DISTANCE,
                        TravelLog::FIELD_LATITUDE,
                        TravelLog::FIELD_LONGITUDE
                    ]);
                }
            ])
            ->where(TravelPlan::FIELD_ID_USER,$userId)
            ->where(TravelPlan::FIELD_STATUS,TravelPlan::ENUM_STATUS_TRAVeLING)
            ->orderBy(TravelPlan::FIELD_CREATED_AT,'desc')
            ->first();
        return $plans;
    }

    /**
     * 生成用户的移动数据
     *
     * @param $userId
     * @param $stepData
     * @return array
     */
    public function travelLog($userId,$stepData)
    {
        if(collect($stepData)->isEmpty()){
            return false;
        }

        $plan = $this->travelingPlan($userId);
        $points = $plan['points'];
        $mathService = app(MathService::class);

        foreach ($stepData as $step){
            $step['step_meter'] = $mathService->stepToMeter($step['step']);
        }

        //计算站点之间的两点距离和实际的地理距离，然后再按照步数的地理距离与站点之间的距离的比例进行转换
        $index = 1;
        $logArray = [];
        $travelLength = 0;
        $point = $points[0];
        $nextPoint = $points[$index];
        $pointLength = $mathService->distanceBetweenPoint($point['latitude'],$point['longitude'],$nextPoint['latitude'],$nextPoint['longitude']);
        $rate = $this->getDistanceWithLocationRate($point,$nextPoint);
        foreach ($stepData as $step){
            //根据比例获取实际的地理坐标
            $stepLength = $rate * $step['step_meter'];
            $travelLength += $stepLength;
            $locationPoint = $mathService->getLocationPoint($point['longitude'],$point['latitude'],$nextPoint['longitude'],$nextPoint['latitude'],$travelLength);
            if($travelLength > $pointLength){
                //重新换下一个坐标开始计算
                $index += 1;

                if($index >= count($points)){
                    //已经计算全部的站点了，循环结束
                    break;
                }

                $point = $nextPoint;
                $nextPoint = $points[$index];
                $travelLength = 0;
                //重新计算两点间的距离和距离比例
                $pointLength = $mathService->distanceBetweenPoint($point['latitude'],$point['longitude'],$nextPoint['latitude'],$nextPoint['longitude']);
                $rate = $this->getDistanceWithLocationRate($point,$nextPoint);
            }

            array_push($logArray,[
                TravelLog::FIELD_ID_TRAVEL_PLAN=>$plan->id,
                TravelLog::FIELD_ID_USER=>$userId,
                TravelLog::FIELD_LATITUDE=>$locationPoint['y'],
                TravelLog::FIELD_LONGITUDE=>$locationPoint['x'],
                TravelLog::FIELD_DISTANCE=>$step['step_meter'],
                TravelLog::FIELD_STEP=>$step['step'],
                TravelLog::FIELD_RUN_AT=>$step['run_at']
            ]);
        }

        return $logArray;
    }

    /**
     * 保存旅行日志
     *
     * @author yezi
     *
     * @param $travelLogs
     * @return mixed
     */
    public function saveTravelLogs($travelLogs)
    {
        foreach ($travelLogs  as &$log){
            $log['created_at'] = Carbon::now();
            $log['updated_at'] = Carbon::now();
        }

        $result = TravelLog::insert($travelLogs);

        return $result;
    }

    /**
     * 用户是否是首次旅行
     *
     * @author yezi
     *
     * @param $userId
     * @return bool
     */
    public function ifFirstTravel($userId)
    {
        $log = TravelLog::query()->where(TravelLog::FIELD_ID,$userId)->value(TravelLog::FIELD_ID);
        if($log){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 终止一切还是旅行中的计划
     *
     * @author yezi
     *
     * @param $userId
     * @return int
     */
    public function stopAllTravelByUserId($userId)
    {
        $result = TravelPlan::query()
            ->where(TravelPlan::FIELD_ID_USER,$userId)
            ->where(TravelPlan::FIELD_STATUS,TravelPlan::ENUM_STATUS_TRAVeLING)
            ->update([TravelPlan::FIELD_STATUS=>TravelPlan::ENUM_STATUS_END]);

        return $result;
    }

    /**
     * 获取地理直线距离和实际距离的比例
     *
     * @author yezi
     *
     * @param $point
     * @param $nextPoint
     * @return float|int
     */
    public function getDistanceWithLocationRate($point,$nextPoint)
    {
        $mathService = app(MathService::class);
        $pointLength = $mathService->distanceBetweenPoint($point['latitude'],$point['longitude'],$nextPoint['latitude'],$nextPoint['longitude']);
        $pointLengthMeter = $mathService->getDistance($point['latitude'],$point['longitude'],$nextPoint['latitude'],$nextPoint['longitude']);

        if($pointLengthMeter == 0){
            //这里会产生数据异常
        }

        $rate = $pointLength / $pointLengthMeter;

        return $rate;
    }

    /**
     * 格式化单条
     *
     * @author yezi
     *
     * @param $plan
     * @return mixed
     */
    public function format($plan)
    {
        if($plan){
            $travelLogs = $plan['travelLogs'];
            if($travelLogs){
                foreach ($travelLogs as $log){
                    $plan['travel_logs'] = $this->formatTravelLog($log);
                }
            }
        }

        return $plan;
    }

    public function traveling($userId)
    {
        $result = TravelPlan::query()
            ->where(TravelPlan::FIELD_ID_USER,$userId)
            ->where(TravelPlan::FIELD_STATUS,TravelPlan::ENUM_STATUS_TRAVeLING)
            ->first();

        return $result;
    }

    public function travelLogBuilder($userId)
    {
        $builder = TravelLog::query()
            ->whereHas(TravelLog::REL_PLAN,function ($query)use($userId){
            $query->where(TravelPlan::FIELD_STATUS,TravelPlan::ENUM_STATUS_TRAVeLING);
            })->where(TravelLog::FIELD_ID_USER,$userId)
            ->orderBy(TravelLog::FIELD_RUN_AT,'desc');

        return $builder;
    }

    /**
     * 格式化旅游日志
     *
     * @author yezi
     *
     * @param $log
     * @return mixed
     */
    public function formatTravelLog($log)
    {
        $log['format_latitude'] = round($log->{TravelLog::FIELD_LATITUDE},4);
        $log['format_longitude'] = round($log->{TravelLog::FIELD_LONGITUDE},4);
        $log['run_at'] = Carbon::parse($log->{TravelLog::FIELD_RUN_AT})->toDateString();
        $log['distance'] = round($log->{TravelLog::FIELD_DISTANCE} / 1000,2);
        $log['log'] = '';
        $log['hotel'] = $this->getTravelHotel($log->id);
        $log['views'] = $this->getViews($log->id);
        $log['foods'] = $this->getFoods($log->id);

        return $log;
    }

    /**
     * 获取景点
     *
     * @author yezi
     *
     * @param $logId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getViews($logId)
    {
        $views = TravelLogPoi::query()
            ->where(TravelLogPoi::FIELD_ID_TRAVEL_ID,$logId)
            ->where(TravelLogPoi::FIELD_TYPE,TravelLogPoi::ENUM_TYPE_VIEW_SPOT)
            ->get();

        return $views;
    }

    /**
     * 获取美食
     *
     * @author yezi
     *
     * @param $logId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getFoods($logId)
    {
        $foods = TravelLogPoi::query()
            ->where(TravelLogPoi::FIELD_ID_TRAVEL_ID,$logId)
            ->where(TravelLogPoi::FIELD_TYPE,TravelLogPoi::ENUM_TYPE_FOOD)
            ->get();

        return $foods;
    }

    /**
     * 获取旅行酒店
     *
     * @author yezi
     *
     * @param $logId
     * @return mixed
     */
    public function getTravelHotel($logId)
    {
        $hotel = TravelLogPoi::query()
            ->where(TravelLogPoi::FIELD_ID_TRAVEL_ID,$logId)
            ->where(TravelLogPoi::FIELD_TYPE,TravelLogPoi::ENUM_TYPE_HOTEL)
            ->value(TravelLogPoi::FIELD_TITLE);

        return $hotel;
    }

    /**
     * 保存沿途咨询
     *
     * @author yezi
     *
     * @param $logId
     * @param $title
     * @param $address
     * @param $type
     * @return mixed
     */
    public function savePoi($logId,$title,$address,$type)
    {
        if($type == TravelLogPoi::ENUM_TYPE_HOTEL){
            $poi = TravelLogPoi::query()
                ->where(TravelLogPoi::FIELD_ID_TRAVEL_ID,$logId)
                ->where(TravelLogPoi::FIELD_TYPE,TravelLogPoi::ENUM_TYPE_HOTEL)
                ->value(TravelLogPoi::FIELD_ID);
            if($poi){
                return $poi;
            }
        }

        $poi = TravelLogPoi::create([
            TravelLogPoi::FIELD_ID_TRAVEL_ID=>$logId,
            TravelLogPoi::FIELD_TITLE=>$title,
            TravelLogPoi::FIELD_ADDRESS=>$address,
            TravelLogPoi::FIELD_TYPE=>$type
        ]);

        return $poi;
    }

    /**
     * 更新途径点的地名
     *
     * @author yezi
     *
     * @param $logId
     * @param $name
     * @param $address
     * @return int
     */
    public function updateLogNameAndAddress($logId,$name,$address)
    {
        $result = TravelLog::query()->where(TravelLog::FIELD_ID,$logId)->update([TravelLog::FIELD_NAME=>$name,TravelLog::FIELD_ADDRESS=>$address]);
        return $result;
    }

}