<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/14 0014
 * Time: 14:49
 */

namespace App\Models;


use Carbon\Carbon;

class SendMessage extends BaseModel
{
    const TABLE_NAME = 'send_messages';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field mobile */
    const FIELD_MOBILE = 'mobile';

    /** Field code */
    const FIELD_CODE = 'code';

    /** Field type 消息类型,1=短息验证码，2=... */
    const FIELD_TYPE = 'type';

    /** Field status 发送状态1=成功，2=失败 */
    const FIELD_STATUS = 'status';

    /** Field expired_at 短信验证码有效期 */
    const Field_expired_at = 'expired_at';

    /** 短息验证码 */
    const ENUM_TYPE_MESSAGE_CODE = 1;

    /** 发送成功 */
    const STATUS_SUCCESS = 1;
    /** 发送失败 */
    const STATUS_FAIL = 2;

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_CODE,
        self::FIELD_MOBILE,
        self::FIELD_TYPE,
        self::Field_expired_at,
        self::FIELD_CREATED_AT,
        self::FIELD_UPDATED_AT,
        self::FIELD_DELETED_AT
    ];

    public static function getEffectMessageCode($mobile,$code)
    {
        /*$result = MessageCode::query()
            ->where(MessageCode::FIELD_MOBILE,$mobile)
            ->where(MessageCode::FIELD_CODE,$code)
            ->where(MessageCode::FIELD_UPDATED_AT,'<=',Carbon::now())
            ->first();

        return $result;*/
    }

}