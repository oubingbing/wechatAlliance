<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/5/26
 * Time: 18:32
 */

namespace App\Http\Controllers\Admin;


use App\Exceptions\WebException;
use App\Http\Service\UserService;
use App\Models\AdminApps;
use App\Models\User;
use App\Models\UserVisitLog;
use App\Models\WechatApp;
use Carbon\Carbon;

class UserController
{
    /**
     * 用户视图
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * 获取用户列表
     *
     * @author yezi
     *
     * @return Response
     */
    public function userList()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size', 20);
        $pageNumber = request()->input('page_number', 1);
        $orderBy    = request()->input('order_by', 'created_at');
        $sortBy     = request()->input('sort_by', 'desc');
        $filter     = request()->input('filter');
        $app        = $user->app();
        $username   = request()->input('username');

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];

        $appId = AdminApps::query()->where(AdminApps::FIELD_ID_ADMIN,$user->id)->value(AdminApps::FIELD_ID_APP);
        if(!$appId){
            return webResponse('没有查询到应用',500);
        }

        $query    = app(UserService::class)->queryBuilder($appId)->filter($username)->sort($orderBy, $sortBy)->done();
        $userList = paginate($query, $pageParams, '*', function ($item) use ($user,$app) {

            $item['gender'] = collect([
                User::ENUM_GENDER_BOY=>'男',
                User::ENUM_GENDER_GIRL=>'女',
                User::ENUM_GENDER_UN_KNOW=>'未知生物',
            ])->get((string)$item[User::FIELD_GENDER],'未知生物');

            $item['service'] = ($item->id == $app->{WechatApp::FIELD_ID_SERVICE}) ? true : false;

            return $item;

        });

        return webResponse('ok',200,$userList);
    }

    /**
     * 用户统计
     * 
     * @author yezi
     * 
     * @return array
     */
    public function userStatistics()
    {
        $user = request()->input('user');

        $appId = AdminApps::query()->where(AdminApps::FIELD_ID_ADMIN,$user->id)->value(AdminApps::FIELD_ID_APP);

        $newUserCount = User::query()
            ->where(User::FIELD_ID_APP,$appId)
            ->whereBetween(User::FIELD_CREATED_AT,[Carbon::now()->startOfDay(),Carbon::now()->endOfDay()])
            ->count(User::FIELD_ID);
        
        $visitUserCount = UserVisitLog::query()
            ->whereHas(UserVisitLog::REL_USER,function ($query)use($appId){
                $query->where(User::FIELD_ID_APP,$appId);
            })
            ->whereBetween(UserVisitLog::FIELD_CREATED_AT,[Carbon::now()->startOfDay(),Carbon::now()->endOfDay()])
            ->distinct(UserVisitLog::FIELD_ID_USER)
            ->pluck(UserVisitLog::FIELD_ID_USER);

        $allUser = User::query()->where(User::FIELD_ID_APP,$appId)->count(User::FIELD_ID);

        return webResponse('ok',200,['new_user'=>$newUserCount, 'visit_user'=>count($visitUserCount),'all_user'=>$allUser]);
    }

    /**
     * 设置黑名单
     *
     * @author 叶子
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws WebException
     */
    public function setBlackList()
    {
        $user    = request()->input('user');
        $blackId = request()->input('black_id');

        if(!$blackId){
            throw new WebException("参数不能为空！",500);
        }

        $userService = app(UserService::class);
        $black = $userService->getBlacklistByUserId($blackId);
        if($black){
            throw new WebException("用户已经加入黑名单，不可重复加入");
        }

        $result = $userService->storeBlackList($blackId);
        return webResponse("加入黑名单成功",200,$result->toArray());
    }

    /**
     * 移出黑名单
     *
     * @author 叶子
     * @param $blackId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws WebException
     */
    public function removeBlackList($blackId)
    {
        $user = request()->input('user');

        if(!$blackId){
            throw new WebException("参数不能为空！",500);
        }

        $userService = app(UserService::class);
        $black       = $userService->getBlacklistByUserId($blackId);
        if(!$black){
            throw new WebException("该用户不在黑名单中");
        }

        $result = $userService->deleteBlackList($blackId);
        return webResponse("移除黑名单成功",200,$result);
    }
}