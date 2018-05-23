<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/28
 * Time: 下午2:36
 */

namespace Tests\Unit;


use App\Http\Logic\SaleFriendLogic;
use App\SaleFriend;
use App\User;
use Tests\TestCase;

class SaleFriendTest extends TestCase
{
    /**
     * @test
     */
    public function save()
    {
        $user = User::find(5);
        $name = '叶子';
        $gender = SaleFriend::ENUM_GENDER_BOY;
        $major = '13信息管理与信息系统';
        $expectation = '美丽的';
        $introduce = '哈哈';

        $sale = new SaleFriendLogic();
        $result = $sale->save($user->id,$name,$gender,$major,$expectation,$introduce,$user->{User::FIELD_ID_COLLEGE});
    }

}