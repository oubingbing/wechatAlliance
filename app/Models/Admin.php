<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/5/26
 * Time: 17:08
 */

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable,SoftDeletes;

    const TABLE_NAME = 'admins';
    protected $table = self::TABLE_NAME;

    /** field id 用户Id */
    const FIELD_ID = 'id';

    /** field username 用户昵称 */
    const FIELD_USER_NAME = 'username';

    /** Field avatar 头像 */
    const FIELD_AVATAR = 'avatar';

    /** Field email */
    const FIELD_EMAIL = 'email';

    /** Field password 密码 */
    const FIELD_PASSWORD = 'password';

    /** Field mobile 手机号码 */
    const FIELD_MOBILE = 'mobile';

    /** Field active_token 账号激活码 */
    const FIELD_ACTIVE_TOKEN = 'active_token';
    /** Field token_expire 激活码失效时间 */
    const FIELD_TOKEN_EXPIRE = 'token_expire';

    /** Field  */

    /** Field status 用户状态，0未激活，1=已激活 */
    const FIELD_STATUS = 'status';

    /** 未激活 */
    const ENUM_STATUS_SLEEP = 0;
    /** 已激活 */
    const ENUM_STATUS_ACTIVATED = 1;

    const USER_AVATAR = 'http://image.kucaroom.com/boy.png';

    const REL_APP = 'app';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_USER_NAME,
        self::FIELD_AVATAR,
        self::FIELD_EMAIL,
        self::FIELD_PASSWORD,
        self::FIELD_MOBILE,
        self::FIELD_STATUS,
        self::FIELD_ACTIVE_TOKEN,
        self::FIELD_TOKEN_EXPIRE
    ];

    protected $casts = [
        self::FIELD_AVATAR => 'array',
    ];

    public function app()
    {
        $appId = AdminApps::query()->where(AdminApps::FIELD_ID_ADMIN,$this->id)->value(AdminApps::FIELD_ID_APP);
        $app = WechatApp::find($appId);
        return $app;
    }

}