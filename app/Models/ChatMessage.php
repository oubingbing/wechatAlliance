<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/17
 * Time: 下午10:05
 */
namespace App\Models;

class ChatMessage extends BaseModel
{
    protected $table = 'chat_messages';

    /** field id */
    const FIELD_ID = 'id';

    /** field from_user_id 发送信息者 */
    const FIELD_ID_FROM_USER = 'from_user_id';

    /** field to_user_id 接受信息者 */
    const FIELD_ID_TO = 'to_user_id';

    /** field content 消息内容 */
    const FIELD_CONTENT = 'content';

    /** field attachments 消息附件 */
    const FIELD_ATTACHMENTS = 'attachments';

    /** field type */
    const FIELD_TYPE = 'type';

    /** field status */
    const FIELD_STATUS = 'status';

    /** field post_at 发送的时间 */
    const FIELD_POST_AT = 'post_at';

    /** field read_at 读信的时间 */
    const FIELD_READ_AT = 'read_at';

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';

    /** 消息类型-文本 */
    const ENUM_TYPE_TEXT = 1;
    /** 消息类型-图片 */
    const ENUM_TYPE_IMAGE = 2;

    /** 接收状态-已发送 */
    const ENUM_STATUS_SEND = 1;
    /** 接受状态-已送达 */
    const ENUM_STATUS_ARRIVE = 2;
    /** 接受状态-已阅读 */
    const ENUM_STATUS_RED = 3;
    /** 接受状态-接受失败 */
    const ENUM_STATUS_FAIL = 4;

    protected $casts = [
        self::FIELD_ATTACHMENTS => 'array',
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_FROM_USER,
        self::FIELD_ID_TO,
        self::FIELD_CONTENT,
        self::FIELD_ATTACHMENTS,
        self::FIELD_TYPE,
        self::FIELD_STATUS,
        self::FIELD_POST_AT,
        self::FIELD_READ_AT,
        self::FIELD_CREATED_AT,
        self::FIELD_UPDATED_AT,
        self::FIELD_DELETED_AT
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class, self::FIELD_ID_FROM_USER)->select(User::FIELD_ID, User::FIELD_NICKNAME, User::FIELD_AVATAR);
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, self::FIELD_ID_TO)->select(User::FIELD_ID, User::FIELD_NICKNAME, User::FIELD_AVATAR);
    }

}