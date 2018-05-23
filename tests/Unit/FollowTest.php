<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/24
 * Time: 下午8:10
 */

namespace Tests\Unit;


use App\Friend;
use Tests\TestCase;

class FollowTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        app(Friend::class);
    }

}