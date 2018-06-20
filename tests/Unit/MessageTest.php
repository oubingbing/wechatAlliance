<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20 0020
 * Time: 14:42
 */

namespace Tests\Unit;


use App\Http\Service\TokenService;
use App\Http\Service\WeChatMessageService;
use Tests\TestCase;

class MessageTest extends TestCase
{
    public function testToken()
    {
        $allianceKey = 'uNy6iug7SmrN3uCY';
        $result = app(TokenService::class)->accessToken($allianceKey);

    }

    public function testSendMessage()
    {
        $allianceKey = 'uNy6iug7SmrN3uCY';
        $Message = new WeChatMessageService($allianceKey);
        $result = $Message->send();

        dd($result);
    }

}