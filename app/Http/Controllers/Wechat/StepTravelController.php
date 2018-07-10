<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/10 0010
 * Time: 15:15
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\StepTravelService;
use App\Http\Service\WeChatRequestService;
use App\Models\User;
use App\Models\WechatApp;

class StepTravelController extends Controller
{
    protected $stepTravelService;

    public function __construct(StepTravelService $stepTravelService)
    {
        $this->stepTravelService = $stepTravelService;
    }

    public function saveStep()
    {
        $user = request()->input('user');
        $encryptedData = request()->input('encrypted_data');
        $iv = request()->input('iv');
        $code = request()->input('code');
        $app = $user->{User::REL_APP};

        $service = new WeChatRequestService($app->{WechatApp::FIELD_APP_KEY},$app->{WechatApp::FIELD_APP_SECRET},$code);
        $runData = $service->getWeRunData($encryptedData,$iv);
        if(!$runData){
            throw new ApiException('您的步数为空！',500);
        }
        $runData = json_decode($runData,true);

        $formatResult = $this->stepTravelService->formatRunDataToDateTimeString($runData['stepInfoList']);

        $result = $this->stepTravelService->getUserNewRunStep($user->id,$formatResult);
        if($result){
            $result = $this->stepTravelService->saveSteps($user->id,$result);
        }

        return collect($result)->toArray();
    }

}