<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/29 0029
 * Time: 10:09
 */

namespace App\Models;


class MessageSession extends BaseModel
{
    const TABLE_NAME = 'message_sessions';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field user_id 发送人 */
    const FIELD_ID_USER = 'user_id';

    /** Field post_phone 发送人的手机号码 */
    const FIELD_POST_PHONE = 'post_phone';

    /** Field receive_phone 接收人的手机号码 */
    const FIELD_RECEIVE_PHONE = 'receive_phone';

    const REL_MESSAGE = 'messages';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_POST_PHONE,
        self::FIELD_RECEIVE_PHONE
    ];

}