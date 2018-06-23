<?php

namespace App\Http\Wechat;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\PostService;
use App\Http\Service\SaleFriendService;
use App\Http\Service\YunPianService;
use App\Models\SaleFriend;
use App\Models\User;
use App\Models\WechatApp;

class IndexController extends Controller
{
    /**
     * 获取用户信息
     *
     * @author yezi
     *
     * @return mixed
     */
    public function index()
    {
        $user = request()->get('user');

        return $user;
    }

    /**
     * 获取应用的状态
     *
     * @author yezi
     *
     * @return mixed
     * @throws ApiException
     */
    public function config()
    {
        $allianceKey = request()->input('app_id');
        if(!$allianceKey){
            throw new ApiException('app_id不能为空',500);
        }

        $result = WechatApp::query()->where(WechatApp::FIELD_ALLIANCE_KEY,$allianceKey)->value(WechatApp::FIELD_STATUS);

        return $result;
    }

    public function service()
    {
        $user = request()->input('user');

        $serviceId = WechatApp::query()->where(WechatApp::FIELD_ID,$user->{User::FIELD_ID_APP})->value(WechatApp::FIELD_ID_SERVICE);

        return $serviceId;
    }

    /**
     * 搜索
     *
     * @author 叶子
     *
     * @return mixed
     * @throws ApiException
     */
    public function search()
    {
        $user = request()->input('user');
        $content = request()->input('content');
        $objType = request()->input('obj_type');

        if(!$content){
            throw new ApiException('搜索内容不能为空！',500);
        }

        if(!$objType){
            throw new ApiException('搜索类型不能为空！',500);
        }

        switch ($objType){
            case 1:
                $result = app(PostService::class)->searchTopic($user,$content);
                break;
            case 2:
                $result = app(SaleFriendService::class)->searchFriend($user,$content);
                break;
            default:
                $result = app(PostService::class)->searchTopic($user,$content);
                break;
        }

        return $result;
    }

    /**
     * 发送验证码
     *
     * @author yezi
     *
     * @return mixed
     * @throws ApiException
     */
    public function getMessageCode()
    {
        $user = request()->input('user');
        $phone = request()->input('phone');

        if(!$phone){
            throw new ApiException('手机号码不能为空！',500);
        }

        $validPhone = validMobile($phone);
        if($validPhone != 1){
            throw new ApiException('手机号码格式错误',500);
        }

        $result = app(YunPianService::class)->sendMessageCode($phone);
        if($result['code'] != 0){
            throw new ApiException('发送失败！',500);
        }

        return $result;
    }
}