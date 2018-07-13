<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13 0013
 * Time: 15:11
 */

namespace App\Http\Service;


use App\Models\TravelPlan;
use App\Models\TravelPlanPoint;
use Carbon\Carbon;

class TravelService
{
    public function saveTravelPlan($userId,$title,$distance)
    {
        $plan = TravelPlan::create([
            TravelPlan::FIELD_ID_USER=>$userId,
            TravelPlan::FIELD_TITLE=>$title,
            TravelPlan::FIELD_DISTANCE=>$distance
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
                TravelPlanPoint::FIELD_SORT=>$key+1,
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

}