<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/18
 * Time: 上午10:32
 */

namespace App\Http\Repository;


use App\Friend;

class FriendRepository
{
    protected $friend;

    public function __construct(Friend $friend)
    {
        $this->friend = $friend;
    }

    /**
     * 新增好友
     *
     * @author yezi
     *
     * @param $userId
     * @param $friendId
     * @return mixed
     */
    public function saveFriend($userId,$friendId)
    {
        $result = $this->friend->create([
            Friend::FIELD_ID_USER=>$userId,
            Friend::FIELD_ID_FRIEND=>$friendId,
        ]);

        return $result;
    }

    /**
     * 根据Id获取朋友
     *
     * @author yezi
     *
     * @param $id
     * @return array
     */
    public function getFriendById($id)
    {
        return $this->friend->query()->find($id);
    }

    public function checkFriend($userId,$friendId)
    {
        return Friend::query()->where(Friend::FIELD_ID_USER,$userId)->where(Friend::FIELD_ID_FRIEND,$friendId)->first();
    }

    public function friendList($userId)
    {
        return $this->friend->query()->with(['friend'])->where(Friend::FIELD_ID_USER,$userId)->get();
    }

}