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
use App\Http\Service\TravelService;

class TravelController extends Controller
{
    protected $travelService;

    public function __construct(TravelService $travelService)
    {
        $this->travelService = $travelService;
    }

    public function createTravelPlan()
    {
        $user = request()->input('user');
        $plans = request()->input('plans');
        $distance = request()->input('distance');
        $title = request()->input('title');

        try {
            \DB::beginTransaction();

            $travel = $this->travelService->saveTravelPlan($user->id,$title,$distance);
            if(!$travel){
                throw new ApiException('新建失败！',500);
            }

            $this->travelService->saveTravelPlanPoint($travel->id,$plans);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new ApiException($e, 60001);
        }

        return $travel;
    }
}