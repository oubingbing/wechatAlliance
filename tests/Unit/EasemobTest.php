<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/28
 * Time: 下午5:16
 */

namespace Tests\Unit;


use App\Http\Logic\EasemobAuthLogic;
use App\Http\Logic\EasemobLogic;
use Tests\TestCase;

class EasemobTest extends TestCase
{
    /**
     * @test
     */
    public function getToken()
    {
        $eas= new EasemobAuthLogic();

        $token = $eas->getToken();

        dump($token);

        self::assertNotEmpty($token);
    }

    /**
     * @test
     */
    public function register()
    {
        $username = 'bingbing';
        $password = 'bingbing';
        $result = app(EasemobLogic::class)->singleRegister($username,$password);

        self::assertNotEmpty($result);

        dd($result);
    }

    /**
     * @test
     */
    public function user()
    {
        $username = 'yezi';

        $result = app(EasemobLogic::class)->user($username);
        dd($result);
    }

    /**
     * @test
     */
    public function send()
    {
        $from = 'yezi';
        $to = ['bingbing'];
        $type = 1;
        $content = '测试';

        $result = app(EasemobLogic::class)->send($from,$to,$type,$content);

        dd($result);
    }

}