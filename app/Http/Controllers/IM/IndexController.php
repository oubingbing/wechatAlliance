<?php

namespace App\Http\Controllers\IM;

use App\Events\Chat;
use App\Http\Controllers\Controller;
use App\Http\Service\GatewayService;
use GatewayClient\Gateway;

class IndexController extends Controller
{
    public function chatRoom()
    {
        return view('test.redis');
    }
    public function sendMessage()
    {
        Gateway::sendToUid("7f0000010b5600000001", "你好呀");
    }

    public function socket()
    {
        return view('test.socket');
    }

    public function bindSocket()
    {
        $clientId = request()->get('client_id');
        $gatewayHelper = app(GatewayService::class);

        $uid      = 110;
        $gatewayHelper->bindUser($clientId,$uid);

        $message = ['client_id'=>$clientId,'type'=>'test'];
        $gatewayHelper->sendToUserId($uid,$message);

        return ['ok'];
    }


}