<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/3
 * Time: 13:33
 */

namespace App\Http\Controllers\Admin;


use App\Exceptions\WebException;
use App\Http\Controllers\Controller;
use App\Http\Service\VideosService;
use App\Models\Videos;
use Exception;

class VideosController extends Controller
{
    private $service;

    public function __construct(VideosService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.videos.index');
    }

    public function create()
    {
        $user  = request()->input('user');
        $v_id  = request()->input('v_id');
        $title = request()->input('title');
        $sort  = request()->input('sort');

        if(!$v_id){
            throw new WebException("视频id不能为空");
        }

        $videos                         = new Videos();
        $videos->{Videos::FIELD_ID_APP} = $user->app()->id;
        $videos->{Videos::FIELD_TITLE}  = $title;
        $videos->{Videos::FIELD_SORT}   = $sort;
        $videos->{Videos::FIELD_V_ID}   = $v_id;

        try {
            \DB::beginTransaction();

            $result = $this->service->storeVideos($videos);

            \DB::commit();
        } catch (Exception $e) {
            \DB::rollBack();
            throw new WebException($e, 500);
        }

        return webResponse('新建成功',200,$result);
    }

    public function videoList()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $app        = $user->app();

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $query      = $this->service->createBuilder($app->id)->done();
        $videoList  = paginate($query, $pageParams, '*', function ($item) use ($user) {

            return $item;

        });

        return webResponse('ok',200,$videoList);
    }

    public function delete($id)
    {
        $user = request()->input('user');
        $app  = $user->app();

        $result = Videos::query()->where(Videos::FIELD_ID_APP,$app->id)->where(Videos::FIELD_ID,$id)->delete();
        return (string)$result;
    }

    public function update($id)
    {
        $user  = request()->input('user');
        $app   = $user->app();
        $v_id  = request()->input('v_id');
        $title = request()->input('title');
        $sort  = request()->input('sort');

        $videos = $this->service->findById($id);
        if(!$videos){
            throw new WebException("视频不存在",500);
        }

        if(!$v_id){
            throw new WebException("视频id不能为空");
        }

        $videos->{Videos::FIELD_V_ID}  = $v_id;
        $videos->{Videos::FIELD_TITLE} = $title;
        $videos->{Videos::FIELD_SORT}  = $sort;
        $result = $videos->save();
        if(!$result){
            throw new WebException("更新失败",500);
        }

        return webResponse('修改成功',200,'');
    }

}