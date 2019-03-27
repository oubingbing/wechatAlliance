<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/10
 * Time: 11:34
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Service\CompareFaceService;
use App\Models\User;

class CompareFaceController extends Controller 
{
    /**
     * 人脸匹配页面
     *
     * @author yezi
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.face.index');
    }

    /**
     * 人脸匹配列表
     *
     * @author yezi
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function faceList()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $orderBy    = request()->input('order_by', 'created_at');
        $sortBy     = request()->input('sort_by', 'desc');
        $username   = request()->input('username');
        $app        = $user->app();


        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $service    = app(CompareFaceService::class);

        $query = $service->queryBuilder($app->id,$username)->sort($orderBy, $sortBy)->done();

        $result = paginate($query, $pageParams, '*', function ($post) {
            return $post;
        });

        return webResponse('ok',200,$result);
    }

}