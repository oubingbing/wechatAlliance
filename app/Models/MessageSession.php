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

    /** Field obj_type 对象类型 1=表白墙，2=卖舍友，3=暗恋匹配，4=密语 */
    const FIELD_OBJ_TYPE = 'obj_type';
    /** Field obj_id 对象ID */
    const FIELD_OBJ_ID = 'obj_id';

    /** obj_type 表白墙 */
    const ENUM_OBJ_TYPE_POST = 1;
    /** obj_type 卖舍友 */
    const ENUM_OBJ_TYPE_SALE_FRIEND = 2;
    /** obj_type 暗恋匹配 */
    const ENUM_OBJ_TYPE_MATCH_LOVE = 3;
    /** obj_type 表白墙 */
    const ENUM_OBJ_TYPE_SECRET_MESSAGE = 4;

    const REL_MESSAGE = 'messages';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_POST_PHONE,
        self::FIELD_RECEIVE_PHONE,
        self::FIELD_OBJ_ID,
        self::FIELD_OBJ_TYPE
    ];

}