<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/15
 * Time: 下午12:02
 */

namespace App\Http\Service;


use App\Exceptions\ApiException;
use App\Models\Comment;
use App\Models\Inbox;
use App\Models\MatchLove;
use App\Models\PartTimeJob;
use App\Models\Post;
use App\Models\Praise;
use App\Models\SaleFriend;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rules\In;

class InboxService
{
    protected $paginateLogic;

    public function __construct(PaginateService $paginateLogic)
    {
        $this->paginateLogic = $paginateLogic;
    }

    /**
     * 往消息盒子投递信息
     *
     * @author yezi
     *
     * @param $fromId
     * @param $toId
     * @param $objId
     * @param $content
     * @param $objType
     * @param $actionType
     * @param $postAt
     * @return mixed
     * @throws ApiException
     */
    public function send($fromId, $toId, $objId, $content, $objType, $actionType, $postAt,$private=0)
    {
        $fromUser = User::query()->find($fromId);
        $toUser   = User::query()->find($toId);

        if (!$fromUser)
            throw new ApiException('用户不存在', 404);

        if (!$toUser)
            throw new ApiException('用户不在', 404);

        $checkResult = $this->checkObj($objId, $objType);
        if (!$checkResult)
            throw new ApiException('对象不存在', 404);

        $result = Inbox::create([
            Inbox::FIELD_ID_FROM     => $fromId,
            Inbox::FIELD_ID_TO       => $toId,
            Inbox::FIELD_ID_OBJ      => $objId,
            Inbox::FIELD_CONTENT     => $content,
            Inbox::FIELD_OBJ_TYPE    => $objType,
            Inbox::FIELD_ACTION_TYPE => $actionType,
            Inbox::FIELD_POST_AT     => $postAt,
            Inbox::FIELD_PRIVATE     => $private
        ]);

        return $result;
    }

    /**
     * 检测信息对象是否存在
     *
     * @author yezi
     *
     * @param $objId
     * @param $objType
     * @return bool
     */
    public function checkObj($objId, $objType)
    {
        $obj = '';

        switch ($objType) {
            case 1:
                $obj = Post::query()->find($objId);
                break;
            case 2:
                $obj = SaleFriend::query()->find($objId);
                break;
            case 3:
                $obj = MatchLove::query()->find($objId);
                break;
            case 4:
                $obj = Comment::query()->find($objId);
                break;
            case 5:
                $obj = Praise::query()->find($objId);
                break;
            case 6:
                $obj = User::query()->find($objId);
                break;
            case 7:
                $obj = PartTimeJob::query()->find($objId);
                break;
        }

        return empty($obj) ? false : true;
    }

    /**
     * 获取用户消息列表
     *
     * @author yezi
     *
     * @param $userId
     * @param $type
     * @param $messageType
     * @param $pageParams
     * @return mixed
     */
    public function getInboxList($userId, $type, $messageType, $pageParams)
    {
        if ($messageType == 0) {
            $messageType = '';
        }

        $builder = Inbox::query()->with(['fromUser', 'toUser'])->where(Inbox::FIELD_ID_TO, $userId);
        if ($type == 0) {
            $builder->when($messageType, function ($query) {
                return $query->where(Inbox::FIELD_READ_AT, null);
            });
        } else {
            $builder->where(Inbox::FIELD_OBJ_TYPE, $type)
                ->when($messageType, function ($query) {
                    return $query->where(Inbox::FIELD_READ_AT, null);
                });
        }

        $builder->orderBy(Inbox::FIELD_CREATED_AT, 'desc');

        $result = $this->paginateLogic->paginate($builder, $pageParams, '*');

        return $result;
    }

    /**
     * 标记消息为已读
     *
     * @author yezi
     *
     * @param $userId
     * @param null $objType
     * @return mixed
     */
    public function readInbox($userId, $objType = null)
    {
        $result = Inbox::query()
            ->where(Inbox::FIELD_ID_TO, $userId)
            ->when($objType, function ($query) use ($objType) {
                $query->where(Inbox::FIELD_OBJ_TYPE, $objType);

                return $query;
            })
            ->update([Inbox::FIELD_READ_AT => Carbon::now()]);

        return $result;
    }

    /**
     * 检测用户是否有新的消息
     *
     * @author yezi
     *
     * @param $userId
     * @param $type
     * @return int
     */
    public function getNewInboxByType($userId, $type)
    {
        if ($type == 0) {
            $result = Inbox::query()
                ->where(Inbox::FIELD_ID_TO, $userId)
                ->where(Inbox::FIELD_READ_AT, null)
                ->count();
        } else {
            $result = Inbox::query()
                ->where(Inbox::FIELD_ID_TO, $userId)
                ->where(Inbox::FIELD_OBJ_TYPE, $type)
                ->where(Inbox::FIELD_READ_AT, null)
                ->count();
        }

        return $result;
    }

    /**
     * 格式化消息盒子列表
     *
     * @author yezi
     *
     * @param $inboxList
     *
     * @return static
     */
    public function formatInboxList($inboxList)
    {
        $result =  collect($inboxList)->map(function ($inbox) {
            return $this->formatInbox($inbox);
        });

        return $result;
    }

    /**
     * 格式化单个消息盒子
     *
     * @author yezi
     *
     * @param $inbox
     *
     * @return mixed
     */
    public function formatInbox($inbox)
    {
        $objType = $inbox->{Inbox::FIELD_OBJ_TYPE};
        $objId   = $inbox->{Inbox::FIELD_ID_OBJ};

        $obj = $this->getObj($objId, $objType);

        $inbox->obj = $obj;

        $inbox->parentObj = !empty($obj) ? $this->getObj($obj->obj_id, $obj->obj_type) : null;

        if($inbox->{Inbox::FIELD_PRIVATE} == Inbox::ENUM_PRIVATE){
            $inbox->fromUser->nickname  = '匿名の同学';
            if($inbox->fromUser->gender == User::ENUM_GENDER_BOY){
                $inbox->fromUser->avatar = 'http://image.kucaroom.com/boy.png';
            }else{
                $inbox->fromUser->avatar = 'http://image.kucaroom.com/girl.png';
            }
        }

        return $inbox;
    }

    /**
     * 获取对象
     *
     * @author yezi
     *
     * @param $objId
     * @param $objType
     *
     * @return static[]
     */
    public function getObj($objId, $objType)
    {
        $obj = '';
        switch ($objType) {
            case 1:
                $obj = Post::query()->find($objId);
                break;
            case 2:
                $obj = SaleFriend::query()->find($objId);
                break;
            case 3:
                $obj = MatchLove::query()->find($objId);
                break;
            case 4:
                $obj = Comment::query()->find($objId);
                break;
            case 5:
                $obj = Praise::query()->find($objId);
                break;
            case 6:
                $obj = User::query()->find($objId);
                break;
            case 7:
                $obj = PartTimeJob::query()->find($objId);
                break;
        }

        return $obj;
    }

}