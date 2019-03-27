<?php

namespace App\Http\Service;

use App\Exceptions\ApiException;
use App\Models\AccessToken;
use App\Models\User;
use App\Models\WechatApp;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenService
{
    /**
     * 获取微信小程序用户登录token
     *
     * @author yezi
     *
     * @param $user
     * @return mixed
     */
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
            $user      = $userLogin->createWeChatUser($openId,$userInfo,$appId);
        }else{
            $user->{User::FIELD_NICKNAME} = $userInfo['nickName'];
            $user->{User::FIELD_AVATAR} = $userInfo['avatarUrl'];
            $user->save();
        }

        $token = $this->getWecChatToken($user);

        return $token;
    }

    /**
     * 请求微信服务器获取access token
     *
     * @author yezi
     *
     * @param $appId
     * @return mixed
     * @throws ApiException
     */
    public function accessToken($appId)
    {
        $weChatApp = WechatApp::query()->find($appId);
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

    /**
     * 获取微信access token
     *
     * @author yezi
     *
     * @param $appId
     * @return mixed
     */
    public function getAccessToken($appId)
    {
        $token = AccessToken::query()->where(AccessToken::FIELD_ID_APP,$appId)->where(AccessToken::FIELD_EXPIRED_AT,'>',Carbon::now())->first();
        if(!$token){
            $result = $this->accessToken($appId);
            $token = AccessToken::create([
                AccessToken::FIELD_ID_APP     => $appId,
                AccessToken::FIELD_TOKEN      => $result['access_token'],
                AccessToken::FIELD_EXPIRED_AT => Carbon::now()->addSecond($result['expires_in'])
            ]);
        }

        return $token['token'];
    }

    /**
     * 获取token
     *
     * @author yezi
     *
     * @return mixed
     * @throws Exception
     */
    public function createApiToken($appId,$userInfo)
    {
        $user = User::where(User::FIELD_ID_APP,$appId)->orderBy(User::FIELD_CREATED_AT,'asc')->where(User::FIELD_ID_OPENID,$userInfo["openId"])->first();
        if(!$user){
            $userLogin = new UserService();
            $user      = $userLogin->createWeChatUserByModel($appId,$userInfo);
        }else{
            $user->{User::FIELD_NICKNAME} = $userInfo['nickName'];
            $user->{User::FIELD_AVATAR}   = $userInfo['avatarUrl'];
            $user->save();
        }

        $token = $this->getWecChatToken($user);

        return $token;
    }
}