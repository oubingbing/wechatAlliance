<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable,SoftDeletes;

    /** field id 用户Id */
    const FIELD_ID = 'id';

    /** field nickname 用户昵称 */
    const FIELD_NICKNAME = 'nickname';

    /** Field app_id 小程序ID */
    const FIELD_ID_APP = 'app_id';

    /** field email 邮箱 */
    const FIELD_EMAIL = 'email';

    /** field password 密码 */
    const FIELD_PASSWORD = 'password';

    /** field mobile 手机 */
    const FIELD_MOBILE = 'mobile';

    /** field open_id */
    const FIELD_ID_OPENID = 'open_id';

    /** file union_id */
    const FIELD_ID_UNION = 'union_id';

    /** field avatar 头像 */
    const FIELD_AVATAR = 'avatar';

    /** field gender 性别 */
    const FIELD_GENDER = 'gender';

    /** field college 学校 */
    const FIELD_ID_COLLEGE = 'college_id';

    /** field city 所在城市 */
    const FIELD_CITY = 'city';

    /** field country 国家 */
    const FIELD_COUNTRY = 'country';

    /** field language 语言 */
    const FIELD_LANGUAGE = 'language';

    /** field province 省份 */
    const FIELD_PROVINCE = 'province';

    /** field type 类型 */
    const FIELD_TYPE = 'type';

    /** field status 用户状态 */
    const FIELD_STATUS = 'status';

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';

    /** 微信用户 */
    const ENUM_TYPE_WE_CHAT_USER = 1;
    /** 超级管理员 */
    const ENUM_TYPE_SUPERVISE = 2;

    /** 用户初始状态 */
    const ENUM_STATUS_INIT = 0;
    /** 用户激活状态 */
    const ENUM_STATUS_ACTIVITY = 1;

    /** gender-男 */
    const ENUM_GENDER_BOY = 1;
    /** gender-女 */
    const ENUM_GENDER_GIRL = 2;
    /** gender 未知 */
    const ENUM_GENDER_UN_KNOW = 0;

    const REL_PROFILE = 'profile';
    const REL_APP = 'app';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::FIELD_NICKNAME,
        self::FIELD_ID_APP,
        self::FIELD_EMAIL,
        self::FIELD_PASSWORD,
        self::FIELD_MOBILE,
        self::FIELD_ID_OPENID,
        self::FIELD_ID_UNION,
        self::FIELD_AVATAR,
        self::FIELD_GENDER,
        self::FIELD_CITY,
        self::FIELD_COUNTRY,
        self::FIELD_LANGUAGE,
        self::FIELD_PROVINCE,
        self::FIELD_TYPE,
        self::FIELD_STATUS,
        self::FIELD_ID_COLLEGE
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function college()
    {
        return $this->belongsTo(Colleges::class,self::FIELD_ID_COLLEGE);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class,UserProfile::FIELD_ID_USER,self::FIELD_ID);
    }

    public function app()
    {
        return $this->belongsTo(WechatApp::class,self::FIELD_ID_APP,WechatApp::FIELD_ID);
    }

}
