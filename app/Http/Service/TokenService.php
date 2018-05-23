<?php

namespace App\Http\Service;

use Tymon\JWTAuth\Facades\JWTAuth;

class TokenService
{
    public function getWecChatToken($user)
    {
        $token = JWTAuth::fromUser($user);

        return $token;
    }

}