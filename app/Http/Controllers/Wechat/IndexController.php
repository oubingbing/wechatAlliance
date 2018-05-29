<?php

namespace App\Http\Wechat;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
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

}