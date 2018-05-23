<?php

namespace App\Http\Wechat;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * 获取用户信息
     *
     * @author yezi
     *
     * @return mixed
     */
    public function index()
    {
        $user = request()->get('user');

        return $user;
    }

}