<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/24 0024
 * Time: 14:51
 */

namespace App\Models;


class WechatApp extends BaseModel
{
    const TABLE_NAME = 'apps';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field name 小程序的名字 */
    const FIELD_NAME = 'name';

    /** Field app_key 微信小程序官方的app key */
    const FIELD_APP_KEY = 'app_key';

    /** Field app_secret 微信小程序官方的密钥 */
    const FIELD_APP_SECRET = 'app_secret';

    /** Field alliance_key 联盟的key */
    const FIELD_ALLIANCE_KEY = 'alliance_key';

    /** Field attachments 小程序相关图片 */
    const FIELD_ATTACHMENTS = 'attachments';

    /** Field domain 小程序的接口域名 */
    const FIELD_DOMAIN = 'domain';

    /** Field college_id 学校id */
    const FIELD_ID_COLLEGE = 'college_id';

    /** Field service_id 客服id */
    const FIELD_ID_SERVICE = 'service_id';

    /** Field mobile 联系人手机号码 */
    const FIELD_MOBILE = 'mobile';

    /** Field status 小程序状态 */
    const FIELD_STATUS = 'status';

    /** status 开启内容审核 */
    const ENUM_STATUS_TO_BE_AUDIT = 1;
    /** status 关闭内容审核*/
    const ENUM_STATUS_ON_LINE = 2;
    /** status 微信审核中 */
    const ENUM_STATUS_WE_CHAT_AUDIT = 3;
    /** status 下线 */
    const ENUM_STATUS_CLOSED = 4;

    const REL_ADMIN_APP = 'adminApp';
    const REL_COLLEGE = 'college';

    protected $casts = [
        self::FIELD_ATTACHMENTS => 'array',
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_NAME,
        self::FIELD_APP_KEY,
        self::FIELD_APP_SECRET,
        self::FIELD_ALLIANCE_KEY,
        self::FIELD_ID_COLLEGE,
        self::FIELD_MOBILE,
        self::FIELD_DOMAIN,
        self::FIELD_STATUS,
        self::FIELD_ID_SERVICE.
        self::FIELD_ATTACHMENTS
    ];

    public function adminApp()
    {
        return $this->hasMany(AdminApps::class,self::FIELD_ID,AdminApps::FIELD_ID_APP);
    }

    public function college()
    {
        return $this->belongsTo(Colleges::class,self::FIELD_ID_COLLEGE,Colleges::FIELD_ID);
    }

}