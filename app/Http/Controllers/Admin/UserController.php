<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/5/26
 * Time: 18:32
 */

namespace App\Http\Controllers\Admin;


use App\Http\Service\PaginateService;
use App\Http\Service\UserService;
use App\Models\AdminApps;
use App\Models\WechatApp;

class UserController
{
    /**
     * 用户视图
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users()
    {
        return view('user.index');
    }

    /**
     * 获取用户列表
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function userList()
    {
        $user = request()->input('user');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $orderBy = request()->input('order_by', 'created_at');
        $sortBy = request()->input('sort_by', 'desc');
        $filter = request()->input('filter');

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];

        $appId = AdminApps::query()->where(AdminApps::FIELD_ID_ADMIN,$user->id)->value(AdminApps::FIELD_ID_APP);
        if(!$appId){
            return webResponse('没有查询到应用',500);
        }

        $query = app(UserService::class)->queryBuilder($appId)->sort($orderBy, $sortBy)->done();

        $userList = app(PaginateService::class)->paginate($query, $pageParams, '*', function ($item) use ($user) {

            return $item;

        });

        return $userList;
    }

}