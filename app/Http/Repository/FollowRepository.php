<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/24
 * Time: 下午5:03
 */

namespace App\Http\Repository;


use App\Follow;

class FollowRepository
{
    protected $follow;

    public function __construct(Follow $follow)
    {
        $this->follow = $follow;
    }

    /**
     * 关注
     *
     * @author yezi
     *
     * @param $userId
     * @param $objId
     * @param $objType
     * @return mixed
     */
    public function createContact($userId, $objId, $objType)
    {
        $result = $this->follow->create([
            Follow::FIELD_ID_USER => $userId,
            Follow::FIELD_ID_OBJ => $objId,
            Follow::FIELD_OBJ_TYPE => $objType,
            Follow::FIELD_STATUS => Follow::ENUM_STATUS_FOLLOW
        ]);

        return $result;
    }

    /**
     * 检测关注
     *
     * @authro yezi
     *
     * @param $userId
     * @param $objId
     * @param $objType
     * @return mixed
     */
    public function checkFollow($userId, $objId, $objType)
    {
        $result = $this->follow->query()
            ->where([
                Follow::FIELD_ID_USER => $userId,
                Follow::FIELD_ID_OBJ => $objId,
                Follow::FIELD_OBJ_TYPE => $objType,
                Follow::FIELD_STATUS => Follow::ENUM_STATUS_FOLLOW
            ])->first();

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
     * @return mixed
     */
    public function breakFollow($userId, $objId, $objType)
    {
        $follow = $this->follow->query()->where([
            Follow::FIELD_ID_USER => $userId,
            Follow::FIELD_ID_OBJ => $objId,
            Follow::FIELD_OBJ_TYPE => $objType
        ])->first();

        if ($follow) {
            $follow->{Follow::FIELD_STATUS} = Follow::ENUM_STATUS_CANCEL_FOLLOW;
        }

        $result = $follow->save();

        return $result;
    }

    /**
     * 获取用户关注列表
     *
     * @author yezi
     *
     * @param $userId
     * @param $objId
     * @param $objType
     * @return mixed
     */
    public function getUserFollow($userId, $objId, $objType)
    {
        $result = $this->follow->query()->where([
            Follow::FIELD_ID_USER => $userId,
            Follow::FIELD_ID_OBJ => $objId,
            Follow::FIELD_OBJ_TYPE => $objType,
            Follow::FIELD_STATUS => Follow::ENUM_STATUS_FOLLOW
        ])->first();

        return $result;
    }

    public function getFollow($userId,$objId,$type)
    {
        return $this->follow->query()->where([
            Follow::FIELD_ID_USER => $userId,
            Follow::FIELD_ID_OBJ => $objId,
            Follow::FIELD_OBJ_TYPE => $type,
            Follow::FIELD_STATUS => Follow::ENUM_STATUS_FOLLOW
        ])->first();
    }

}