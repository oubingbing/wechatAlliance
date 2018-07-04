<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/29 0029
 * Time: 10:10
 */

namespace App\Models;


class SecretMessage extends BaseModel
{
    const TABLE_NAME = 'secret_messages';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field post_user_id 发送人 */
    const FIELD_ID_POST_USER = 'post_user_id';

    /** Field receive_user_id 接收人用户地 */
    const FIELD_ID_RECEIVE_USER = 'receive_user_id';

    /** Field message_session_id 消息会话ID */
    const FIELD_ID_MESSAGE_SESSION = 'message_session_id';

    /** Field number 消息编号 */
    const FIELD_NUMBER = 'number';

    /** Field content 发送的文本内容 */
    const FIELD_CONTENT = 'content';

    /** Field attachments 发送的附件 */
    const FIELD_ATTACHMENTS = 'attachments';

    /** Field password_code 读信验证码 */
    const FIELD_CODE = 'code';

    /** Field status 是否已读，1=未读，2=已读 */
    const FIELD_STATUS = 'read_status';

    /** Field delay_at 延期发送的时间 */
    const FIELD_DELAY_AT = 'delay_at';

    /** Field send_at 短信发送的日期 */
    const FIELD_SEND_AT = 'send_at';

    /** status-未读 */
    const ENUM_STATUS_UN_READ = 1;
    /** status-已读 */
    const ENUM_STATUS_READ = 2;

    /** SendImmediately=1 */
    const ENUM_SEND_IMMEDIATELY = 1;
    /** SendDelay=2 */
    const ENUM_SEND_DELAY = 2;

    const REL_MESSAGE_SESSION = 'messageSession';

    protected $casts = [
        self::FIELD_ATTACHMENTS => 'array',
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_POST_USER,
        self::FIELD_ID_RECEIVE_USER,
        self::FIELD_ID_MESSAGE_SESSION,
        self::FIELD_NUMBER,
        self::FIELD_CONTENT,
        self::FIELD_ATTACHMENTS,
        self::FIELD_CODE,
        self::FIELD_STATUS,
        self::FIELD_DELAY_AT,
        self::FIELD_SEND_AT
    ];

    public function messageSession()
    {
        return $this->belongsTo(MessageSession::class,self::FIELD_ID_MESSAGE_SESSION,MessageSession::FIELD_ID);
    }

}