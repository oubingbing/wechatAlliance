<?php

namespace App\Http\Service;

use App\Exceptions\ApiException;
use App\Models\User;
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
}