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

    /** Field domain 小程序的接口域名 */
    const FIELD_DOMAIN = 'domain';

    /** Field college_id 学校id */
    const FIELD_ID_COLLEGE = 'college_id';

    /** Field mobile 联系人手机号码 */
    const FIELD_MOBILE = 'mobile';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_NAME,
        self::FIELD_APP_KEY,
        self::FIELD_APP_SECRET,
        self::FIELD_ALLIANCE_KEY,
        self::FIELD_ID_COLLEGE,
        self::FIELD_MOBILE,
        self::FIELD_DOMAIN
    ];

}