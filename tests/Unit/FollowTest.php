<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/24
 * Time: 下午8:10
 */

namespace Tests\Unit;


use App\Friend;
use App\Http\Service\AnimeFaceService;
use App\Http\Service\FormIdService;
use App\Http\Service\TencentService;
use Tests\TestCase;

class FollowTest extends TestCase
{
    /**
     * @test
     */
    public function testForm()
    {
        $result = app(FormIdService::class)->getForIdByUserId(3937);

        dd($result);
    }

    /**
     * vendor/bin/phpunit tests/unit/FollowTest.php --filter=compareface
     * 
     * @test
     */
    public function compareface()
    {
        $urla = "http://article.qiuhuiyi.cn/%E5%BE%AE%E4%BF%A1%E5%9B%BE%E7%89%87_20221107143345.jpg";
        $urlb = "http://article.qiuhuiyi.cn/%E5%BE%AE%E4%BF%A1%E5%9B%BE%E7%89%87_20221107143350.jpg";
        $result = app(TencentService::class)->compareFace($urla,$urlb);
        dd($result);
    }

    /**
     * vendor/bin/phpunit tests/unit/FollowTest.php --filter=phone
     * 
     * @test
     */
    public function phone()
    {
        $result = validMobile("13425144866");
        dd($result);
    }

    /**
     * vendor/bin/phpunit tests/unit/FollowTest.php --filter=anime
     * 
     * @test
     */
    public function anime()
    {
        $url = "http://article.qiuhuiyi.cn/%E5%BE%AE%E4%BF%A1%E5%9B%BE%E7%89%87_20221116141826.jpg";
        $result = app(AnimeFaceService::class)->animeFace($url);
        dd($result);
    }

}