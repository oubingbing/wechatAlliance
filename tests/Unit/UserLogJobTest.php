<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2018/3/18
 * Time: 下午3:24
 */

namespace Tests\Unit;


use App\Jobs\UserLogs;
use App\User;
use Tests\TestCase;

class UserLogJobTest extends TestCase
{
    /**
     * @test
     */
    public function logInfo()
    {
        $user = User::first();

        dispatch((new UserLogs($user))->onQueue('record_visit_log'));

        dump('主任务结束');
    }

}