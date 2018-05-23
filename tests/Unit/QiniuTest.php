<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/13
 * Time: 下午5:31
 */

namespace Tests\Unit;


use App\Http\QiNiuLogic\QiNiuLogic;
use Tests\TestCase;

class QiniuTest extends TestCase
{
    /**
     * @test
     */
    public function uploadToken()
    {
        $token = app(QiNiuLogic::class)->uploadToken();

        dd($token);
    }

}