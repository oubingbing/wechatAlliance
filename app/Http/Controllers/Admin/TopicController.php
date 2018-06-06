<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/6 0006
 * Time: 10:13
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Service\PaginateService;
use App\Http\Service\TopicService;
use App\Models\Topic;

class TopicController extends Controller
{
    public function index()
    {
        return view('admin.topic.index');
    }

    public function createView()
    {
        return view('admin.topic.create');
    }

    /**
     * 新建话题
     * 
     * @author yezi
     * 
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store()
    {
        $user = request()->input('user');
        $title = request()->input('title');
        $content = request()->input('content');
        $attachments = request()->input('attachments');
        $app = $user->app();

        if(!$content){
            return webResponse('话题内容不能为空！',500);
        }

        $topic = Topic::create([
            Topic::FIELD_ID_USER=>$user->id,
            Topic::FIELD_ID_APP=>$app->id,
            Topic::FIELD_USER_TYPE=>Topic::ENUM_USER_TYPE_ADMIN,
            Topic::FIELD_TITLE=>$title,
            Topic::FIELD_CONTENT=>$content,
            Topic::FIELD_ATTACHMENTS=>collect(collect($attachments)->pluck('key'))->toArray()
        ]);

        return webResponse('新建成功',200,$topic);
    }

    public function topicList()
    {
        $user = request()->input('user');
        $pageSize = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $orderBy = request()->input('order_by', 'created_at');
        $sortBy = request()->input('sort_by', 'desc');
        $app = $user->app();

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];

        $query = Topic::query()
            ->where(Topic::FIELD_ID_USER,$user->id)
            ->where(Topic::FIELD_USER_TYPE,Topic::ENUM_USER_TYPE_ADMIN)
            ->orderBy(Topic::FIELD_CREATED_AT, 'desc');

        $topics = app(PaginateService::class)->paginate($query, $pageParams, '*', function ($topic){

            $topic->{Topic::FIELD_ATTACHMENTS} = collect($topic->{Topic::FIELD_ATTACHMENTS})->map(function ($item){
               return 'http://image.kucaroom.com/'.$item;
            });

            return $topic;
        });

        return webResponse('ok',200,$topics);
    }

    public function upTopic($id)
    {
        $user = request()->input('user');

        $result = app(TopicService::class)->updateStatus($user->id,$id,Topic::ENUM_STATUS_UP);
        if($result){
            return webResponse('上架成功！',200);
        }

        return webResponse('上架失败！',200);
    }

    public function downTopic($id)
    {
        $user = request()->input('user');

        $result = app(TopicService::class)->updateStatus($user->id,$id,Topic::ENUM_STATUS_DOWN);
        if($result){
            return webResponse('下架成功！',200);
        }

        return webResponse('下架失败！',200);
    }
}