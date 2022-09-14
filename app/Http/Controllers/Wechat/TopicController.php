<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/6 0006
 * Time: 17:48
 */

namespace App\Http\Controllers\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\CommentService;
use App\Models\Comment;
use App\Models\Topic;
use App\Models\User;

class TopicController extends Controller
{
    /**
     * 获取话题
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function topic()
    {
        $user  = request()->input('user');

        $topic = Topic::query()
            ->where(Topic::FIELD_ID_APP,$user->{User::FIELD_ID_APP})
            ->where(Topic::FIELD_STATUS,Topic::ENUM_STATUS_UP)
            ->orderBy(Topic::FIELD_CREATED_AT,'DESC')
            ->first();

        return $topic;
    }

    /**
     * 获取话题详情
     *
     * @author yezi
     *
     * @param $id
     * @return Model|null|static|static[]
     * @throws ApiException
     */
    public function topicDetail($id)
    {
        $user  = request()->input('user');

        $topic = Topic::query()->find($id);
        if(!$topic){
            throw new ApiException('话题不存在',500);
        }

        $topic->{Topic::FIELD_VIEW_NUMBER} += 1;
        $topic->save();

        return $topic;
    }

    /**
     * 点赞评论
     *
     * @author yezi
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function praiseTopic($id)
    {
        $user  = request()->input('user');
        $topic = Topic::query()->with(['comments'])->find($id);

        $topic->{Topic::FIELD_PRAISE_NUMBER} += 1;
        $topic->save();

        return $topic;
    }

    /**
     * 获取话题详情
     *
     * @author yezi
     *
     * @param $id
     * @return mixed
     */
    public function topicComments($id)
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size',10);
        $pageNumber = request()->input('page_number',1);
        $orderBy    = request()->input('order_by','created_at');
        $sortBy     = request()->input('sort_by','desc');

        $pageParams = ['page_size'=>$pageSize, 'page_number'=>$pageNumber];
        $query      = Comment::query()
            ->where(Comment::FIELD_ID_OBJ,$id)
            ->where(Comment::FIELD_OBJ_TYPE,Comment::ENUM_OBJ_TYPE_TOPIC)
            ->orderBy($orderBy,$sortBy);

        $comments   = paginate($query,$pageParams, '*',function($comment)use($user){
            $comment= app(CommentService::class)->formatSingleComments($comment, $user);

            if($comment['can_delete'] == false){
                //是否是超管
                if($user->{User::FIELD_TYPE} == User::ENUM_TYPE_SUPERVISE){
                    $comment['can_delete'] = true;
                }
            }

            return $comment;
        });

        return $comments;
    }

    public function getMostNewTopComments($id)
    {
        $user     = request()->input('user');
        $orderBy  = request()->input('order_by','created_at');
        $sortBy   = request()->input('sort_by','desc');
        $time     = request()->input('time');

        $comments = Comment::query()
            ->where(Comment::FIELD_ID_OBJ,$id)
            ->where(Comment::FIELD_OBJ_TYPE,Comment::ENUM_OBJ_TYPE_TOPIC)
            ->when($time,function ($query)use($time){
                return $query->where(Comment::FIELD_CREATED_AT,'>=',$time);
            })
            ->orderBy($orderBy,$sortBy)
            ->limit(10)
            ->get();

        $comments    = collect($comments)->map(function ($comment)use($user){
            $comment = app(CommentService::class)->formatSingleComments($comment, $user);

            if($comment['can_delete'] == false){
                //是否是超管
                if($user->{User::FIELD_TYPE} == User::ENUM_TYPE_SUPERVISE){
                    $comment['can_delete'] = true;
                }
            }

            return $comment;
        });

        return $comments;
    }

}