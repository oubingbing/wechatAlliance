<?php

namespace App\Http\Service;

use App\Exceptions\ApiException;
use App\Models\User;
use App\Models\WechatApp;
use GuzzleHttp\Client;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenService
{
    public function getWecChatToken($user)
    {
        $token = JWTAuth::fromUser($user);

        return $token;
    }

    /**
     * 获取token
     *
     * @author yezi
     *
     * @return mixed
     * @throws Exception
     */
    public function createToken($userInfo,$openId,$appId)
    {
        if (empty($openId) || empty($userInfo)){
            throw new ApiException('用户信息不用为空',6000);
        }

        $user = User::where(User::FIELD_ID_OPENID,$openId)->where(User::FIELD_ID_APP,$appId)->first();

        if(!$user){
            $userLogin = new UserService();
            $user = $userLogin->createWeChatUser($openId,$userInfo,$appId);
        }

        $token = $this->getWecChatToken($user);

        return $token;
    }

    public function accessToken($appId)
    {
        $weChatApp = WechatApp::query()->where(WechatApp::FIELD_ALLIANCE_KEY,$appId)->first();
        if(!$weChatApp){
            throw new ApiException('不是有效的key',6000);
        }

        if($weChatApp->{WechatApp::FIELD_STATUS} === WechatApp::ENUM_STATUS_TO_BE_AUDIT){
            throw new ApiException('小程序处于审核中，无法使用后台服务！',6001);
        }

        $weChatAppId = $weChatApp->{WechatApp::FIELD_APP_KEY};
        $secret = $weChatApp->{WechatApp::FIELD_APP_SECRET};

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$weChatAppId&secret=$secret";

        $http = new Client;
        $response = $http->get($url);

        $result = json_decode((string) $response->getBody(), true);

        return $result;
    }

    public function getAccessToken($appId)
    {
        $result = $this->accessToken($appId);

        return $result['access_token'];
    }
}