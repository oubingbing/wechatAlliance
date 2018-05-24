<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/18
 * Time: 上午10:31
 */

namespace App\Http\Service;


use App\Http\Repository\ChatRepository;
use App\Http\Repository\FriendRepository;
use App\Models\ChatMessage;
use App\Models\Friend;

class FriendService
{
    /**
     * 新增好友
     *
     * @author yezi
     *
     * @param $userId
     * @param $friendId
     * @return mixed
     */
    public function createFriend($userId, $friendId)
    {
        $result = Friend::create([
            Friend::FIELD_ID_USER=>$userId,
            Friend::FIELD_ID_FRIEND=>$friendId,
        ]);

        return $result;
    }

    /**
     * 检测是否已存在该好友
     *
     * @author yezi
     *
     * @param $userId
     * @param $friendId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function checkFriendUnique($userId, $friendId)
    {
        $result = Friend::query()->where(Friend::FIELD_ID_USER,$userId)->where(Friend::FIELD_ID_FRIEND,$friendId)->first();
        return $result;
    }

    /**
     * 好友列表
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function friends($userId)
    {
        $friends = Friend::query()->with(['friend'])->where(Friend::FIELD_ID_USER,$userId)->get();

        return $friends;
    }

    public function format($friend)
    {
        $friend->newMessageNumber = ChatMessage::query()
            ->where(ChatMessage::FIELD_ID_FROM_USER, $friend->{Friend::FIELD_ID_FRIEND})
            ->where(ChatMessage::FIELD_ID_TO, $friend->{Friend::FIELD_ID_USER})
            ->where(ChatMessage::FIELD_READ_AT, null)
            ->count();

        return $friend;
    }

}