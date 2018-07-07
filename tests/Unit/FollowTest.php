<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/24
 * Time: 下午8:10
 */

namespace Tests\Unit;


use App\Friend;
use App\Http\Service\FormIdService;
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

}