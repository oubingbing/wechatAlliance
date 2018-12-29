<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/28
 * Time: 上午11:47
 */

namespace App\Http\Service;


use App\Models\Colleges;
use App\Models\Comment;
use App\Models\Follow;
use App\Models\SaleFriend;
use App\Models\User;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;

class SaleFriendService
{
    protected $commentLogic;
    protected $builder;

    public function __construct(CommentService $commentLogic)
    {
        $this->commentLogic = $commentLogic;
    }

    /**
     * 新增
     *
     * @author yezi
     *
     * @param $userId
     * @param $name
     * @param $gender
     * @param $major
     * @param $expectation
     * @param $introduce
     * @param null $collegeId
     * @param $attachments
     * @return mixed
     */
    public function save($userId, $name, $gender, $major, $expectation, $introduce, $attachments, $collegeId = null)
    {
        $result = SaleFriend::create([
            SaleFriend::FIELD_ID_OWNER    => $userId,
            SaleFriend::FIELD_ID_COLLEGE  => $collegeId,
            SaleFriend::FIELD_NAME        => $name,
            SaleFriend::FIELD_GENDER      => $gender,
            SaleFriend::FIELD_MAJOR       => $major,
            SaleFriend::FIELD_EXPECTATION => $expectation,
            SaleFriend::FIELD_INTRODUCE   => $introduce,
            SaleFriend::FIELD_ATTACHMENTS => $attachments
        ]);

        return $result;
    }

    /**
     * 构建查询语句
     *
     * @author yezi
     *
     * @param $user
     * @param $type
     * @param $just
     *
     * @return $this
     */
    public function builder($user,$type,$just)
    {
        $this->builder = SaleFriend::query()
            ->whereHas(SaleFriend::REL_USER,function ($query)use($user){
                $query->where(User::FIELD_ID_APP,$user->{User::FIELD_ID_APP});
            })
            ->with(['poster','comments'])
            ->when($type,function ($query)use($user,$type){
                if($type == 2){
                    $query->whereHas('follows',function ($query)use($user,$type){
                        $query->where(Follow::FIELD_ID_USER,$user->id)->where(Follow::FIELD_STATUS,Follow::ENUM_STATUS_FOLLOW);
                    });
                }

                return $query;
            })
            ->when($just,function ($query)use($user){
                $query->where(SaleFriend::FIELD_ID_OWNER,$user->id);

                return $query;
            })
            ->when($user->{User::FIELD_ID_COLLEGE},function ($query)use($user){
                return $query->where(SaleFriend::FIELD_ID_COLLEGE,$user->{User::FIELD_ID_COLLEGE});
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
    public function sort($orderBy,$sortBy)
    {
        $this->builder->orderBy($orderBy,$sortBy);

        return $this;
    }

    /**
     * 返回查询语句
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
     * 格式化单挑数据
     *
     * @author yezi
     *
     * @param $saleFriend
     * @param $user
     * @return mixed
     */
    public function formatSingle($saleFriend, $user)
    {
        if(is_array($saleFriend->{SaleFriend::FIELD_ATTACHMENTS}[0])){
            $attachments = $saleFriend->{SaleFriend::FIELD_ATTACHMENTS};
            foreach ($attachments as &$attachment){
                $attachment = $attachment['url'];
            }
            $saleFriend->{SaleFriend::FIELD_ATTACHMENTS} = $attachments;
        }

        $saleFriend->can_delete = $this->canDeleteSaleFriend($saleFriend, $user);

        $saleFriend->can_chat = $saleFriend->{SaleFriend::FIELD_ID_OWNER}==$user->id?true:false;

        $saleFriend['comments'] = collect($this->commentLogic->formatBatchComments($saleFriend['comments'], $user))->sortByDesc(Comment::FIELD_CREATED_AT)->values();

        $followService = app(FollowService::class);

        $saleFriend['follow'] = $followService->checkFollow($user->id, $saleFriend['id'], Follow::ENUM_OBJ_TYPE_SALE_FRIEND) ? true : false;

        $saleFriend['follow_number'] = $followService->countFollow($saleFriend['id'], Follow::ENUM_OBJ_TYPE_SALE_FRIEND);

        return $saleFriend;
    }

    /**
     * 是否可以删除当前数据
     *
     * @author yezi
     *
     * @param $saleFriend
     * @param $user
     * @return bool
     */
    public function canDeleteSaleFriend($saleFriend, $user)
    {
        $poster = $saleFriend['poster'];
        if ($poster->id == $user->id || $user->{User::FIELD_TYPE} == User::ENUM_TYPE_SUPERVISE) {
            return true;
        } else {
            return false;
        }

    }

    public function convertAttachments($attachments)
    {
        if(is_array($attachments[0])){
            $tempArray = [];
            foreach ($attachments as $attachment){
                array_push($tempArray,$attachment['url']);
            }
            $attachments = $tempArray;
        }
        return $attachments;
    }

    /**
     * 是否可以与之聊天
     *
     * @author yezi
     *
     * @param $saleFriend
     * @param $user
     * @return bool
     */
    public function canChat($saleFriend, $user)
    {
        $poster = $saleFriend['poster'];
        if ($poster->id != $user->id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 搜索被卖的人
     *
     * @author yezi
     *
     * @param $appId
     * @param $topic
     * @return mixed
     */
    public function searchFriend($user,$topic)
    {
        $appId = $user->{User::FIELD_ID_APP};

        $result = SaleFriend::query()->with(['poster','comments'])->whereHas(SaleFriend::REL_USER,function ($query)use($appId){
            $query->where(User::FIELD_ID_APP,$appId);
        })->where(SaleFriend::FIELD_NAME,$topic)->get();

        $result = collect($result)->map(function ($item)use($user){
            return $this->formatSingle($item,$user);
        });

        return $result;
    }

}