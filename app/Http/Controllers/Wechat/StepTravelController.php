<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/10 0010
 * Time: 15:15
 */

namespace App\Http\Controllers\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\StepTravelService;
use App\Http\Service\TravelService;
use App\Http\Service\WeChatRequestService;
use App\Models\RunStep;
use App\Models\TravelLog;
use App\Models\User;
use App\Models\WechatApp;
use Carbon\Carbon;

class StepTravelController extends Controller
{
    protected $stepTravelService;

    public function __construct(StepTravelService $stepTravelService)
    {
        $this->stepTravelService = $stepTravelService;
    }

    /**
     * 收集用户运动步数
     *
     * @author yezi
     *
     * @return array|string
     * @throws ApiException
     */
    public function saveStep()
    {
        $user          = request()->input('user');
        $encryptedData = request()->input('encrypted_data');
        $iv            = request()->input('iv');
        $code          = request()->input('code');
        $app           = $user->{User::REL_APP};

        $service = new WeChatRequestService($app->{WechatApp::FIELD_APP_KEY},$app->{WechatApp::FIELD_APP_SECRET},$code);
        $runData = $service->getWeRunData($encryptedData,$iv);
        if(!$runData){
            throw new ApiException('您的步数为空！',500);
        }

        $runData      = json_decode($runData,true);
        $formatResult = $this->stepTravelService->formatRunDataToDateTimeString($runData['stepInfoList']);
        $checkToday   = $this->stepTravelService->ifRunDataInToday($user->id);

        try {
            \DB::beginTransaction();
        
            //如果当天有数据就只更新数据
            if($checkToday){
                $this->stepTravelService->updateTodayRunData($user->id,$formatResult);
            }
    
            $result = $this->stepTravelService->getUserNewRunStep($user->id,$formatResult);
            if($result){
                $result = $this->stepTravelService->saveSteps($user->id,$result);
            }

            $travelService = app(TravelService::class);
            $plan          = $travelService->traveling($user->id);

            if($plan){
                $travelLog = $travelService->getLastTravelLog($plan->id);
                $points    = $travelService->getNotFinishPoint($plan->id);
                if($travelLog){
                    $length = $travelLog->{TravelLog::FIELD_TOTAL_LENGTH};
                }else{
                    $length = 0;
                }
                //步数旅行
                $newStepData   = $this->stepTravelService->canTravelRunData($user->id);
                $travelLogData = $travelService->travelLog($user->id,$newStepData,$plan,$points,$length);
                if($travelLogData){
                    $travelService->saveTravelLogs($travelLogData);
                }
            }
            
            $this->stepTravelService->updateTypeIsTodayRunData($user->id,$formatResult);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new ApiException($e, 60001);
        }

        return collect($result)->toArray();
    }

    /**
     * 获取用户统计的步数
     *
     * @author yezi
     *
     * @return array
     */
    public function statisticStep()
    {
        $user = request()->input('user');
        $userId = request()->input('user_id',0);
        if(!$userId){
            $userId = $user->id;
        }

        $todayStep = $this->stepTravelService->todayStep($userId);
        $totalStep = $this->stepTravelService->statisticStep($userId);

        return [
            'today_step'=>$todayStep,
            'total_step'=>round($totalStep/10000,1)
        ];
    }

    /**
     * 获取用户微信步数列表
     *
     * @author yezi
     *
     * @return mixed
     */
    public function steps()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $orderBy    = request()->input('order_by', 'created_at');
        $sortBy     = request()->input('sort_by', 'desc');

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $query      = $this->stepTravelService->stepBuilder($user->id)->sort($orderBy,$sortBy)->done();
        $steps      = paginate($query, $pageParams, [RunStep::FIELD_ID,RunStep::FIELD_RUN_AT,RunStep::FIELD_STEP], function ($item) use ($user) {
            return $this->stepTravelService->formatStep($item);
        });

        $steps['page_data'] = collect($steps['page_data'])->filter(function ($item){
           if($item->{RunStep::FIELD_RUN_AT} != Carbon::now()->toDateString()){
                return $item;
           }
        })->toArray();

        return $steps;
    }

    /**
     * 获取用户微信步数列表
     *
     * @author yezi
     *
     * @return mixed
     */
    public function rankingList()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $orderBy    = request()->input('order_by', 'created_at');
        $sortBy     = request()->input('sort_by', 'desc');

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $selectData = [
            RunStep::FIELD_ID,
            RunStep::FIELD_RUN_AT,
            RunStep::FIELD_STEP,
            RunStep::FIELD_ID_USER
        ];

        $query = $this->stepTravelService
            ->stepBuilder()
            ->selectToday()
            ->filterByApp($user)
            ->sort(RunStep::FIELD_STEP,'desc')
            ->done()
            ->with([RunStep::REL_USER=>function($query){
                $query->select([
                    User::FIELD_ID,
                    User::FIELD_NICKNAME,
                    User::FIELD_AVATAR
                ]);
            }]);

        $steps = paginate($query, $pageParams, $selectData, function ($item) use ($user) {
            return $this->stepTravelService->formatStep($item);
        });

        return $steps;
    }

    public function getMyRank()
    {
        $user = request()->input("user");
        $userId = request()->input("user_id",0);
        if(!$userId){
            $userId = $user->id;
        }

        $selectData = [
            RunStep::FIELD_ID,
            RunStep::FIELD_RUN_AT,
            RunStep::FIELD_STEP,
            RunStep::FIELD_ID_USER
        ];

        $stepRanks = $this->stepTravelService
            ->stepBuilder()
            ->selectToday()
            ->filterByApp($user)
            ->sort(RunStep::FIELD_STEP,'desc')
            ->done()
            ->select($selectData)
            ->with([RunStep::REL_USER=>function($query){
                $query->select([
                    User::FIELD_ID,
                    User::FIELD_NICKNAME,
                    User::FIELD_AVATAR
                ]);
            }])
            ->get();

        $index    = 0;
        $userStep = '';
        foreach ($stepRanks as $key => $item){
            if($item->{RunStep::FIELD_ID_USER} == $userId){
                $userStep = $item;
                $index    = $key+1;
                break;
            }
        }

        return [
            'rank' => $index,
            'data' => $userStep
        ];
    }

}