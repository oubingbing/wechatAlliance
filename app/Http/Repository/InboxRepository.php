<?php

namespace App\Http\Repository;

use App\Inbox;
use Carbon\Carbon;


class InboxRepository
{
    protected $inbox;

    public function __construct(Inbox $inbox)
    {
        $this->inbox = $inbox;
    }

    public function store($fromId, $toId, $objId, $content, $objType, $actionType, $postAt)
    {
        $result = Inbox::create([
            Inbox::FIELD_ID_FROM => $fromId,
            Inbox::FIELD_ID_TO => $toId,
            Inbox::FIELD_ID_OBJ => $objId,
            Inbox::FIELD_CONTENT => $content,
            Inbox::FIELD_OBJ_TYPE => $objType,
            Inbox::FIELD_ACTION_TYPE => $actionType,
            Inbox::FIELD_POST_AT => $postAt
        ]);

        return $result;
    }

    /**
     * 获取用户的消息
     *
     * @author yezi
     *
     * @param $userId
     * @param $type
     * @param $messageType
     * @return mixed
     */
    public function userInbox($userId,$type,$messageType)
    {
        $builder = $this->inbox->query()->with(['fromUser','toUser'])->where(Inbox::FIELD_ID_TO, $userId);
        if($type == 0){
            $result = $builder->when($messageType,function ($query){
                return $query->where(Inbox::FIELD_READ_AT,null);
            })->get();
        }else{
            $result = $builder->where(Inbox::FIELD_OBJ_TYPE,$type)
                ->when($messageType,function ($query){
                    return $query->where(Inbox::FIELD_READ_AT,null);
                })->get();
        }
        return $result;
    }

    public function countNewInboxByType($userId,$type)
    {
        if($type == 0){
            $result = $this->inbox->query()
                ->where(Inbox::FIELD_ID_TO,$userId)
                ->where(Inbox::FIELD_READ_AT,null)
                ->count();
        }else{
            $result = $this->inbox->query()
                ->where(Inbox::FIELD_ID_TO,$userId)
                ->where(Inbox::FIELD_OBJ_TYPE,$type)
                ->where(Inbox::FIELD_READ_AT,null)
                ->count();
        }

        return $result;
    }

    public function readInbox($userId,$objType=null)
    {
        $result = Inbox::query()
            ->where(Inbox::FIELD_ID_TO,$userId)
            ->when($objType,function ($query)use($objType){
                $query->where(Inbox::FIELD_OBJ_TYPE,$objType);

                return $query;
            })
            ->update([Inbox::FIELD_READ_AT=>Carbon::now()]);

        return $result;
    }

}