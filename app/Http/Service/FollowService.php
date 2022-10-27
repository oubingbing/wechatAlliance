<?php

namespace App\Http\Service;

use App\Exceptions\ApiException;
use App\Models\Follow;
use App\Models\User;

class FollowService
{
    private $builder;

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
        $result = Follow::query()->where([
            Follow::FIELD_ID_USER  => $userId,
            Follow::FIELD_ID_OBJ   => $objId,
            Follow::FIELD_OBJ_TYPE => $objType
        ])->delete();

        if($objType == Follow::ENUM_OBJ_TYPE_USER){
            $userService = app(UserService::class);
            $beFollowUser = $userService->getUserById($objId);
            if(!$beFollowUser){
                throw new ApiException("用户不存在",5000);
            }
    
            $followUser = $userService->getUserById($userId);
            if(!$followUser){
                throw new ApiException("用户不存在",5000);
            }

            $myFans = $this->countFollow($objId,Follow::ENUM_OBJ_TYPE_USER);
            $beFollowUser->{User::FIELD_FANS_NUM} = $myFans;
            $beFollowUser->save();
    
            $myFollow = $this->countMyFollow($$userId,Follow::ENUM_OBJ_TYPE_USER);
            $followUser->{User::FIELD_FOLLOW_NUM} = $myFollow;
            $followUser->save();
        }

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
    public function userFollow($followUserId, $beFollowUserId)
    {
        $userService = app(UserService::class);
        $beFollowUser = $userService->getUserById($beFollowUserId);
        if(!$beFollowUser){
            throw new ApiException("用户不存在",5000);
        }

        $followUser = $userService->getUserById($followUserId);
        if(!$followUser){
            throw new ApiException("用户不存在",5000);
        }

        $followExists = Follow::query()->where(Follow::FIELD_ID_USER,$followUserId)->where(Follow::FIELD_ID_OBJ,$beFollowUserId)->where(Follow::FIELD_OBJ_TYPE,Follow::ENUM_OBJ_TYPE_USER)->first();
        if($followExists){
            throw new ApiException("已关注无需重复操作",5000);
        }

        $result = Follow::create([
            Follow::FIELD_ID_USER            => $followUserId,
            Follow::FIELD_ID_OBJ             => $beFollowUserId,
            Follow::FIELD_OBJ_TYPE           => Follow::ENUM_OBJ_TYPE_USER,
            Follow::FIELD_STATUS             => Follow::ENUM_STATUS_FOLLOW,
            Follow::FIELD_FOLLOW_NICKNAME    => $followUser->{User::FIELD_NICKNAME},
            Follow::FIELD_FOLLOW_AVATAR      => $followUser->{User::FIELD_AVATAR},
            Follow::FIELD_BE_FOLLOW_NICKNAME => $beFollowUser->{User::FIELD_NICKNAME},
            Follow::FIELD_BE_FOLLOW_AVATAR   => $beFollowUser->{User::FIELD_AVATAR},
        ]);
        if(!$result){
            throw new ApiException("关注失败，请稍后重试",5000);
        }

        $myFans = $this->countFollow($beFollowUserId,Follow::ENUM_OBJ_TYPE_USER);
        $beFollowUser->{User::FIELD_FANS_NUM} = $myFans;
        $beFollowUser->save();

        $myFollow = $this->countMyFollow($followUserId,Follow::ENUM_OBJ_TYPE_USER);
        $followUser->{User::FIELD_FOLLOW_NUM} = $myFollow;
        $followUser->save();

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

    public function countMyFollow($objId,$type)
    {
        $result = Follow::query()
            ->where([
                Follow::FIELD_ID_USER   => $objId,
                Follow::FIELD_OBJ_TYPE => $type,
                Follow::FIELD_STATUS   => Follow::ENUM_STATUS_FOLLOW
            ])->count();

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
    public function query($userId,$type)
    {
        $this->builder = Follow::query()->where(Follow::FIELD_OBJ_TYPE,Follow::ENUM_OBJ_TYPE_USER);
        if($type == 1){
            //我的关注
            $this->builder->where(Follow::FIELD_ID_USER,$userId);
        }

        if($type == 2){
            //我的粉丝
            $this->builder->where(Follow::FIELD_ID_OBJ,$userId);
        }

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
    public function formatSingle($item)
    {
        return $item;
    }

}