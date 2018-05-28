<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/5
 * Time: 下午4:43
 */

namespace App\Http\Service;


use App\Models\User;

class UserService
{
    private $builder;

    /**
     * 新增用户
     *
     * @author yezi
     *
     * @param $openId
     * @param $data
     *
     * @return mixed
     */
    public function createWeChatUser($openId, $data,$appId)
    {
        $result = User::create([
            User::FIELD_ID_OPENID => $openId,
            User::FIELD_ID_APP    => $appId,
            User::FIELD_NICKNAME  => $data['nickName'],
            User::FIELD_GENDER    => $data['gender'] ? $data['gender'] : 0,
            User::FIELD_AVATAR    => $data['avatarUrl'],
            User::FIELD_CITY      => $data['city'] ? $data['city'] : '无',
            User::FIELD_COUNTRY   => $data['country'] ? $data['country'] : '无',
            User::FIELD_PROVINCE  => $data['province'] ? $data['province'] : '无',
            User::FIELD_LANGUAGE  => $data['language'],
            User::FIELD_TYPE      => User::ENUM_TYPE_WE_CHAT_USER,
            User::FIELD_STATUS    => User::ENUM_STATUS_ACTIVITY
        ]);

        return $result;
    }

    public function queryBuilder($appId)
    {
        $builder = User::query()->where(User::FIELD_ID_APP,$appId);

        $this->builder = $builder;
        return $builder;
    }

    public function sort($orderBy)
    {
        $this->builder->where($orderBy['order_by'],$orderBy['sort']);

        return $this;
    }

    public function done()
    {
        return $this->builder;
    }

}