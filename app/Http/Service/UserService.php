<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/5
 * Time: 下午4:43
 */

namespace App\Http\Service;


use App\Models\BlackList;
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
     * 新增用户
     *
     * @author yezi
     * @param $userInfo
     * @return mixed
     */
    public function createWeChatUserByModel($appId,$userInfo)
    {
        $result = User::create([
            User::FIELD_ID_APP    => $userInfo["app_id"],
            User::FIELD_ID_OPENID => $userInfo["openId"],
            User::FIELD_NICKNAME  => $userInfo['nickName'],
            User::FIELD_GENDER    => $userInfo['gender'] ? $userInfo['gender'] : 0,
            User::FIELD_AVATAR    => $userInfo['avatarUrl'],
            User::FIELD_CITY      => $userInfo['city'] ? $userInfo['city'] : '无',
            User::FIELD_COUNTRY   => $userInfo['country'] ? $userInfo['country'] : '无',
            User::FIELD_PROVINCE  => $userInfo['province'] ? $userInfo['province'] : '无',
            User::FIELD_LANGUAGE  => $userInfo['language'],
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
        $builder = User::query()->with([
            User::REL_BLACK_LIST
        ])->where(User::FIELD_ID_APP,$appId);

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

    public function filter($username)
    {
        if($username){
            $this->builder->where(User::FIELD_NICKNAME,'like','%'.$username.'%');
        }

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

    /**
     * 获取用户资料
     *
     * @author yezi
     *
     * @param $id
     * @return mixed
     */
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
    public function saveProfile($user,$name,$grade,$number,$major,$college)
    {
        $profile = $this->getProfileById($user->id);
        if($profile){
            $profile->{UserProfile::FIELD_NAME}           = $name;
            $profile->{UserProfile::FIELD_GRADE}          = $grade;
            $profile->{UserProfile::FIELD_STUDENT_NUMBER} = $number;
            $profile->{UserProfile::FIELD_COLLEGE}        = $college;
            $profile->{UserProfile::FIELD_MAJOR}          = $major;
            $profile->save();
        }else{
            $profile = UserProfile::create([
                UserProfile::FIELD_ID_USER        => $user->id,
                UserProfile::FIELD_NAME           => $name,
                UserProfile::FIELD_GRADE          => $grade,
                UserProfile::FIELD_STUDENT_NUMBER => $number,
                UserProfile::FIELD_COLLEGE        => $college,
                UserProfile::FIELD_MAJOR          => $major,
                UserProfile::FIELD_NICKNAME       => $user->{User::FIELD_NICKNAME},
                UserProfile::FIELD_AVATAR         => $user->{User::FIELD_AVATAR}
            ]);
        }

        return $profile;
    }

    /**
     * 验证参数
     *
     * @author yezi
     *
     * @param $request
     * @return array
     */
    public function validProfile($request)
    {
        $rules = [
            'username'       => 'required',
            'student_number' => 'required',
            'grade'          => 'required',
            'major'          => 'required',
            'college'        => 'required',
            'mobile'         => 'required',
            'code'           => 'required | numeric',
        ];
        $message = [
            'username.required'       => '名字不能为空！',
            'student_number.required' => '学号不能为空！',
            'grade.numeric'           => '年级不能为空！',
            'major.required'          => '专业不能为空！',
            'college.required'        => '学院不能为空！',
            'mobile.numeric'          => '手机不能为空！',
            'code.required'           => '验证码不能为空！',
            'code.numeric'            => '验证码必须是数字！'
        ];
        $validator = \Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['valid'=>false,'message'=>$errors->first()];
        }else{
            return ['valid'=>true,'message'=>'success'];
        }
    }

    public function getPhoneById($id)
    {
        return User::query()->where(User::FIELD_ID,$id)->value(User::FIELD_MOBILE);
    }

    /**
     * 查找黑名单
     *
     * @author yezi
     * @param $userId
     * @return $this
     */
    public function getBlacklistByUserId($userId)
    {
        $result = BlackList::query()->where(BlackList::FIELD_ID_USER,$userId)->first();
        return $result;
    }

    public function storeBlackList($userId)
    {
        $result = BlackList::create([
            BlackList::FIELD_ID_USER=>$userId
        ]);

        return $result;
    }

    public function deleteBlackList($userId)
    {
        $result = BlackList::query()->where(BlackList::FIELD_ID_USER,$userId)->delete();
        return $result;
    }
}