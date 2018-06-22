<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/5
 * Time: 下午4:43
 */

namespace App\Http\Service;


use App\Models\User;
use App\Models\UserProfile;

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

    /**
     * 新建查询构造器
     *
     * @author yezi
     *
     * @param $appId
     * @return $this
     */
    public function queryBuilder($appId)
    {
        $builder = User::query()->where(User::FIELD_ID_APP,$appId);

        $this->builder = $builder;

        return $this;
    }

    /**
     * 排序
     *
     * @author yezi
     *
     * @param $orderBy
     * @return $this
     */
    public function sort($orderBy,$sortBy)
    {
        $this->builder->orderBy($orderBy,$sortBy);

        return $this;
    }

    /**
     * 返回查询构造器
     *
     * @author yezi
     *
     * @return mixed
     */
    public function done()
    {
        return $this->builder;
    }

    public function getUserById($id)
    {
        $user = User::find($id);

        return $user;
    }

    /**
     * 获取个人资料
     *
     * @author yezi
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getProfileById($userId)
    {
        $profile = UserProfile::query()->where(UserProfile::FIELD_ID_USER,$userId)->first();

        return $profile;
    }

    /**
     * 跟新手机号码
     *
     * @author yezi
     *
     * @param $userId
     * @param $mobile
     * @return int
     */
    public function updateMobile($userId,$mobile)
    {
        $result = User::query()->where(User::FIELD_ID,$userId)->update([User::FIELD_MOBILE=>$mobile]);

        return $result;
    }

    /**
     * 保存个人资料
     *
     * @author yezi
     *
     * @param $name
     * @param $grade
     * @param $number
     * @param $major
     * @param $college
     * @return mixed
     */
    public function saveProfile($name,$grade,$number,$major,$college)
    {
        $profile = UserProfile::create([
            UserProfile::FIELD_NAME=>$name,
            UserProfile::FIELD_GRADE=>$grade,
            UserProfile::FIELD_STUDENT_NUMBER=>$number,
            UserProfile::FIELD_COLLEGE=>$college,
            UserProfile::FIELD_MAJOR=>$major
        ]);

        return $profile;
    }

}