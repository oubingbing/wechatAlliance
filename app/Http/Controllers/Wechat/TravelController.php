<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13 0013
 * Time: 15:11
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\StepTravelService;
use App\Http\Service\TravelService;

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
        $user = request()->input('user');
        $plans = request()->input('plans');
        $distance = request()->input('distance');
        $title = request()->input('title');

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
            $firstTravel = $this->travelService->ifFirstTravel($user->id);
            if($firstTravel){
                $stepData = app(StepTravelService::class)->getUserAllRunData($user->id);
                $travelLogData = $this->travelService->travelLog($user->id,$stepData);
                if(!$travelLogData){
                    throw new ApiException("获取数据失败！",500);
                }
                $result = $this->travelService->saveTravelLogs($travelLogData);
                if(!$result){
                    throw new ApiException("保存数据失败！",500);
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
        $user = request()->input('user');

        $result = $this->travelService->travelingPlan($user->id);

        return $result;
    }
}