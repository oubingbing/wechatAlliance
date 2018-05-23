<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/15
 * Time: 下午2:07
 */

namespace App\Http\Repository;


use App\User;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserById($userId)
    {
        return $this->user->find($userId);
    }

}