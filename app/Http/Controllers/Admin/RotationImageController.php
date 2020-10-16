<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/16 0016
 * Time: 16:12
 */

namespace App\Http\Controllers\Admin;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\RotationImageService;
use App\Models\RotationImageModel;

class RotationImageController extends Controller
{
    protected $service;

    public function __construct(RotationImageService $postService)
    {
        $this->service = $postService;
    }

    public function index()
    {
        return view('admin.rotation.index');
    }

    public function create()
    {
        $user  = request()->input('user');
        $image = request()->input("image");
        $app   = $user->app();

        if (empty($image)){
            throw new ApiException("参数错误");
        }

        $domain = env("QI_NIU_DOMAIN");
        $result = $this->service->store($app->id,0,$image);
        $result->url = $domain."/".$result->url;
        return webResponse('ok',200,$result);
    }

    public function ListData()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $orderBy    = request()->input('order_by', 'created_at');
        $sortBy     = request()->input('sort_by', 'desc');
        $app        = $user->app();

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];

        $query = RotationImageModel::query()
            ->where(RotationImageModel::FIELD_ID_APP,$app->id)
            ->orderBy(RotationImageModel::FIELD_SORT, 'asc');

        $domain = env("QI_NIU_DOMAIN");
        $list = paginate($query, $pageParams, '*', function ($item)use($domain){
            $item->url = $domain."/".$item->url;
            return $item;
        });

        return webResponse('ok',200,$list);
    }

    public function delete()
    {
        $user = request()->input('user');
        $app  = $user->app();
        $id   = request()->input("id");

        if (empty($id)){
            throw new ApiException('参数错误');
        }

        $result = $this->service->delete($app->id,$id);
        if (!$result){
            throw new ApiException("删除失败");
        }

        return webResponse('删除成功',200,[]);
    }

    public function updateAppId()
    {
        $user     = request()->input('user');
        $app      = $user->app();
        $wechatId = request()->input("wechat_id");
        $id       = request()->input("id");

        $result = $this->service->update($id,$app->id,$wechatId);
        if (!$result){
            throw new ApiException("修改失败");
        }

        return webResponse('修改成功',200,[]);
    }

}