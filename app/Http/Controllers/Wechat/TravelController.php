<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13 0013
 * Time: 15:11
 */

namespace App\Http\Controllers\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\StepTravelService;
use App\Http\Service\TravelService;
use App\Models\RunStep;
use Carbon\Carbon;

class TravelController extends Controller
{
    protected $travelService;

    public function __construct(TravelService $travelService)
    {
        $this->travelService = $travelService;
    }

    /**
     * 新建旅行计划
     *
     * @author yezi
     *
     * @return mixed
     * @throws ApiException
     */
    public function createTravelPlan()
    {
        $user     = request()->input('user');
        $plans    = request()->input('plans');
        $distance = request()->input('distance');
        $title    = request()->input('title');

        if(collect($plans)->count() <= 1){
            throw new ApiException('站点要两个以上',500);
        }

        $plans = collect(collect($plans)->sortBy('id'))->toArray();

        try {
            \DB::beginTransaction();

            //终止所有还在旅行中的计划
            $this->travelService->stopAllTravelByUserId($user->id);

            //新建旅行计划
            $travel = $this->travelService->saveTravelPlan($user->id,$title,$distance);
            if(!$travel){
                throw new ApiException('新建失败！',500);
            }
            $this->travelService->saveTravelPlanPoint($travel->id,$plans);

            //是否是首次旅行，是的话就是用用户的步数进行旅行
            $firstTravel  = $this->travelService->ifFirstTravel($user->id);
            $plan         = $this->travelService->travelingPlan($user->id);
            if($firstTravel){
                $stepData = app(StepTravelService::class)->getUserAllRunData($user->id);
                $stepData = collect($stepData)->filter(function ($item){
                    //过滤掉当天的数据
                    if(Carbon::parse($item->{RunStep::FIELD_RUN_AT})->toDateString() != Carbon::now()->toDateString()){
                        return $item;
                    }
                });
            }else{
                //或者是否有可用的步数
                $stepData = app(StepTravelService::class)->canTravelRunData($user->id);
            }

            if($stepData){
                //进行旅行操作
                $travelLogData = $this->travelService->travelLog($user->id,$stepData,$plan,$plan['points']);
                if($travelLogData){
                    $result = $this->travelService->saveTravelLogs($travelLogData);
                    if(!$result){
                        throw new ApiException("保存数据失败！",500);
                    }
                }
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new ApiException($e, 60001);
        }

        return $travel;
    }

    /**
     * 获取用户正在进行的旅游计划
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function plan()
    {
        $user   = request()->input('user');
        $plan   = $this->travelService->travelingPlan($user->id);
        $result = $this->travelService->format($plan);

        return $result;
    }

    /**
     * 旅行日志
     *
     * @author yezi
     *
     * @return mixed
     */
    public function travelLogs()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $planId     = request()->input('plan_id',null);

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $query      = $this->travelService->travelLogBuilder($user->id,$planId);
        $logs       = paginate($query, $pageParams, ['*'], function ($item) use ($user) {
            return $this->travelService->formatTravelLog($item);
        });

        return $logs;
    }

    /**
     * 保存咨询
     *
     * @author yezi
     *
     * @return mixed
     * @throws ApiException
     */
    public function createPoi()
    {
        $user    = request()->input('user');
        $logId   = request()->input('log_id');
        $title   = request()->input('title');
        $address = request()->input('address');
        $type    = request()->input('type');

        $result = $this->travelService->savePoi($logId,$title,$address,$type);
        if(!$result){
            throw new ApiException('保存失败！',500);
        }

        return $result;
    }

    /**
     * 更新旅行日志
     *
     * @author yezi
     *
     * @return int
     * @throws ApiException
     */
    public function updateLog()
    {
        $user     = request()->input('user');
        $logId    = request()->input('log_id');
        $name     = request()->input('name');
        $address  = request()->input('address');
        $province = request()->input('province','');
        $city     = request()->input('city','');
        $district = request()->input('district','');

        $result   = $this->travelService->updateLogNameAndAddress($logId,$name,$address,$province,$city,$district);
        if(!$result){
            throw new ApiException('更新失败！',500);
        }

        return $result;
    }

    /**
     * 旅行报告
     *
     * @param $planId
     * @return array
     */
    public function travelReport($planId)
    {
        $user   = request()->input('user');

        $travel = $this->travelService->statisticsTravel($planId);
        $poi    = $this->travelService->statisticsPoi($planId);
        $points = $this->travelService->getStartAndEndPoint($planId);

        return [
            'travel' => $travel,
            'poi'    => $poi,
            'points' => $points
        ];
    }

    /**
     * 旅行记录列表
     *
     * @author yezi
     *
     * @return mixed
     */
    public function plans()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $orderBy    = request()->input('order_by', 'created_at');
        $sortBy     = request()->input('sort_by', 'desc');

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $query      = $this->travelService->stepBuilder($user->id)->sort($orderBy,$sortBy)->done();
        $plans      = paginate($query, $pageParams, ['*'], function ($item) use ($user) {
            return $this->travelService->formatTravel($item);
        });

        return $plans;
    }

    /**
     * 获取旅行计划的详情
     *
     * @author yezi
     *
     * @param $id
     * @return mixed
     */
    public function planDetail($id)
    {
        $user   = request()->input('user');
        $plan   = $this->travelService->getPlanById($id);
        $result = $this->travelService->format($plan);

        return $result;
    }
}