<?php

namespace App\Http\Service;

use GatewayClient\Gateway;

class GatewayService
{
    private $address = '47.106.131.223:1236';

    public function __construct()
    {
        Gateway::$registerAddress = $this->address;
    }

    /**
     * 将用户与客户端id进行绑定
     *
     * @author yezi
     *
     * @param $clientId
     * @param $userId
     */
    public function bindUser($clientId,$userId)
    {
        Gateway::bindUid($clientId, $userId);
    }

    /**
     * 推送消息给客户端
     *
     * @author yezi
     *
     * @param $userId
     * @param array $data
     */
    public function sendToUserId($userId,$data=[])
    {
        Gateway::sendToUid($userId, json_encode($data));
    }

}