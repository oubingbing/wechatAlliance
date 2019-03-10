<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/13
 * Time: 下午6:16
 */

namespace App\Http\Service;



use App\Models\Colleges;
use App\Models\Follow;
use App\Models\MessageSession;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class PostService
{
    protected $commentLogic;
    protected $builder;

    public function __construct(CommentService $commentLogic)
    {
        $this->commentLogic = $commentLogic;
    }

    /**
     * 保存新增的贴子
     *
     * @author yezi
     *
     * @param $user
     * @param $content
     * @param null $imageUrls
     * @param null $location
     * @param null $private
     * @param null $topic
     *
     * @return mixed
     */
    public function save($user, $content, $imageUrls = null, $location = null, $private = null, $topic = null)
    {
        $result = Post::create([
            Post::FIELD_ID_POSTER   => $user->{User::FIELD_ID},
            Post::FIELD_ID_COLLEGE  => $user->{User::FIELD_ID_COLLEGE},
            Post::FIELD_CONTENT     => $content,
            Post::FIELD_ATTACHMENTS => $imageUrls,
            Post::FIELD_PRIVATE     => $private,
            Post::FIELD_TOPIC       => !empty($topic) ? $topic : '无'
        ]);

        return $result;
    }

    /**
     * 构建查询
     *
     * @author yezi
     *
     * @param $user
     * @param $type
     * @param $just
     *
     * @return $this
     */
    public function builder($user, $type, $just)
    {
        $this->builder = Post::query()->with(['poster'=>function($query){
            $query->select(User::FIELD_ID,User::FIELD_NICKNAME,User::FIELD_AVATAR,User::FIELD_GENDER,User::FIELD_CREATED_AT);
        }, 'praises', 'comments'])
            ->whereHas(Post::REL_USER,function ($query)use($user){
                $query->where(User::FIELD_ID_APP,$user->{User::FIELD_ID_APP});
            })
            ->when($type, function ($query) use ($user, $type) {
                if ($type == 2) {
                    $query->whereHas('follows', function ($query) use ($user, $type) {
                        $query->where(Follow::FIELD_ID_USER, $user->id)->where(Follow::FIELD_STATUS, Follow::ENUM_STATUS_FOLLOW);
                    });
                }
                return $query;
            })
            ->when($just, function ($query) use ($user) {
                $query->where(Post::FIELD_ID_POSTER, $user->id);
                return $query;
            })
            ->when($user->{User::FIELD_ID_COLLEGE}, function ($query) use ($user) {
                return $query->where(Post::FIELD_ID_COLLEGE, $user->{User::FIELD_ID_COLLEGE});
            });

        return $this;
    }

    /**
     * 搜索过滤
     *
     * @author yezi
     *
     * @param $filter
     */
    public function filter($filter)
    {
        $this->builder->when($filter,function ($query)use($filter){
            return $query->where(function ($query)use($filter){
                $query->where(Post::FIELD_TOPIC,'like',"%$filter%")
                    ->orWhere(Post::FIELD_CONTENT,'like',"%$filter%")
                    ->orWhere(function ($query)use($filter){
                        $query->whereHas(Post::REL_MESSAGE_SESSION,function ($query)use($filter){
                            $query->where(MessageSession::FIELD_RECEIVE_PHONE,$filter);
                        });
                    })
                    ->orWhere(function ($query)use($filter){
                        $query->where(Post::FIELD_PRIVATE,Post::ENUM_NOT_PRIVATE)->whereHas(Post::REL_USER,function ($query)use($filter){
                            $query->where(User::FIELD_NICKNAME,'like',"%$filter%");
                        });
                    });
            });
        });

        return $this;
    }

    /**
     * 排序
     *
     * @author yezi
     *
     * @param $orderBy
     * @param $sortBy
     *
     * @return $this
     */
    public function sort($orderBy, $sortBy)
    {
        $this->builder->orderBy($orderBy, $sortBy);

        return $this;
    }

    /**
     * 返回查询构建
     *
     * @author yezi
     *
     * @return mixed
     */
    public function done()
    {
        return $this->builder;
    }

    /**
     * 获取最新的贴子
     *
     * @author yezi
     *
     * @param $user
     * @param null $time
     *
     * @return mixed
     */
    public function getPostList($user, $time = null)
    {
        $posts = Post::with(['poster', 'praises', 'comments'])
            ->whereHas(Post::REL_USER,function ($query)use($user){
                $query->where(User::FIELD_ID_APP,$user->{User::FIELD_ID_APP});
            })
            ->where(Post::FIELD_ID_COLLEGE, $user->{User::FIELD_ID_COLLEGE})
            ->when($time, function ($query) use ($time) {
                return $query->where(Post::FIELD_CREATED_AT, '>', $time);
            })
            ->orderBy(Post::FIELD_CREATED_AT, 'desc')
            ->get();

        return $posts;

    }

    /**
     * 格式化单挑贴子
     *
     * @author yezi
     *
     * @param $post
     * @param $user
     *
     * @return $this
     */
    public function formatSinglePost($post, $user,$private=true)
    {
        if (collect($post)->toArray()) {
            $post = collect($post)->toArray();

            if($post['private'] == Post::ENUM_PRIVATE && $private){
                $post['poster']['nickname'] = '校友';
                $post['poster']['avatar'] = '';
            }

            Carbon::setLocale('zh');
            $post[Post::FIELD_CREATED_AT] = Carbon::parse($post[Post::FIELD_CREATED_AT])->diffForHumans();

            $post['follow'] = app(FollowService::class)->checkFollow($user->id, $post['id'], Follow::ENUM_OBJ_TYPE_POST) ? true : false;

            $post[ Post::FIELD_ATTACHMENTS ] = collect($post[ Post::FIELD_ATTACHMENTS ])->map(function ($item) {
                if (is_null($item) || $item == null) {
                    $item = '';
                }
                return $item;
            });

            $post['praises'] = app(PraiseService::class)->formatBatchPraise($post['praises']);

            $post['comments'] = $this->commentLogic->formatBatchComments($post['comments'], $user, $post);

            if ($post[ Post::FIELD_ID_POSTER ] == $user->{User::FIELD_ID}) {
                $post['can_delete'] = true;
                $post['can_chat']   = false;
            } else {
                $post['can_delete'] = false;
                $post['can_chat']   = true;
            }

            if($post['can_delete'] == false){
                //是否是超管
                if($user->{User::FIELD_TYPE} == User::ENUM_TYPE_SUPERVISE){
                    $post['can_delete'] = true;
                }
            }
        }

        return $post;
    }

    /**
     * 搜索被表白的人
     *
     * @author yezi
     *
     * @param $appId
     * @param $topic
     * @return mixed
     */
    public function searchTopic($user,$topic)
    {
        $appId = $user->{User::FIELD_ID_APP};

        $result = Post::query()->with(['poster', 'praises', 'comments'])->whereHas(Post::REL_USER,function ($query)use($appId){
            $query->where(User::FIELD_ID_APP,$appId);
        })->where(Post::FIELD_TOPIC,$topic)->get();

        $result = collect($result)->map(function ($item)use($user){
            return $this->formatSinglePost($item, $user);
        });

        return $result;
    }

}