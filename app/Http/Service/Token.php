<?php

namespace App\Http\Service;

use Tymon\JWTAuth\Facades\JWTAuth;

class Token
{
    public function getWecChatToken($user)
    {
        $token = JWTAuth::fromUser($user);

        return $token;
    }

    public function refreshWeChatToken()
    {

    }


}