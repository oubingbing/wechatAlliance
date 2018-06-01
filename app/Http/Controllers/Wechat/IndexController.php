<?php

namespace App\Http\Wechat;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\PostService;
use App\Http\Service\SaleFriendService;
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

}