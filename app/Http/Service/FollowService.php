<?php

namespace App\Http\Service;

use App\Http\Repository\FollowRepository;
use App\Models\Follow;

class FollowService
{
    /**
     * 关注
     *
     * @author yezi
     *
     * @param $userId
     * @param $objId
     * @param $objType
     *
     * @return mixed
     */
    public function follow($userId, $objId, $objType)
    {
        $result = Follow::query()
            ->where([
                Follow::FIELD_ID_USER  => $userId,
                Follow::FIELD_ID_OBJ   => $objId,
                Follow::FIELD_OBJ_TYPE => $objType,
                Follow::FIELD_STATUS   => Follow::ENUM_STATUS_FOLLOW
            ])->first();;
        if (!$result) {
            $result = Follow::create([
                Follow::FIELD_ID_USER  => $userId,
                Follow::FIELD_ID_OBJ   => $objId,
                Follow::FIELD_OBJ_TYPE => $objType,
                Follow::FIELD_STATUS   => Follow::ENUM_STATUS_FOLLOW
            ]);
        }

        return $result;
    }

    /**
     * 取消关注
     *
     * @author yezi
     *
     * @param $userId
     * @param $objId
     * @param $objType
     *
     * @return mixed
     */
    public function cancelFollow($userId, $objId, $objType)
    {
        $follow = Follow::query()->where([
            Follow::FIELD_ID_USER  => $userId,
            Follow::FIELD_ID_OBJ   => $objId,
            Follow::FIELD_OBJ_TYPE => $objType
        ])->first();

        if ($follow) {
            $follow->{Follow::FIELD_STATUS} = Follow::ENUM_STATUS_CANCEL_FOLLOW;
        }

        $result = $follow->save();

        return $result;
    }

    /**
     * 用户关注好友
     *
     * @author yezi
     *
     * @param $userId
     * @param $objId
     * @param $objType
     *
     * @return mixed
     */
    public function userFollow($userId, $objId, $objType)
    {
        $result = Follow::query()->where([
            Follow::FIELD_ID_USER  => $userId,
            Follow::FIELD_ID_OBJ   => $objId,
            Follow::FIELD_OBJ_TYPE => $objType,
            Follow::FIELD_STATUS   => Follow::ENUM_STATUS_FOLLOW
        ])->first();

        return $result;
    }

    /**
     * 检测关注
     *
     * @author yezi
     *
     * @param $userId
     * @param $objId
     * @param $type
     *
     * @return mixed
     */
    public function checkFollow($userId, $objId, $type)
    {
        $result = Follow::query()
            ->where([
                Follow::FIELD_ID_USER  => $userId,
                Follow::FIELD_ID_OBJ   => $objId,
                Follow::FIELD_OBJ_TYPE => $type,
                Follow::FIELD_STATUS   => Follow::ENUM_STATUS_FOLLOW
            ])->first();

        return $result;
    }

    public function countFollow($objId,$type)
    {
        $result = Follow::query()
            ->where([
                Follow::FIELD_ID_OBJ   => $objId,
                Follow::FIELD_OBJ_TYPE => $type,
                Follow::FIELD_STATUS   => Follow::ENUM_STATUS_FOLLOW
            ])->count();

        return $result;
    }

}