<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/13
 * Time: 下午2:58
 */

namespace App\Models;


class Inbox extends BaseModel
{
    protected $table = 'inboxes';

    /** field id */
    const FIELD_ID = 'id';

    /** field from_id 发信人Id */
    const FIELD_ID_FROM = 'from_id';

    /** field to_id 接收人Id */
    const FIELD_ID_TO = 'to_id';

    /** field content 内容 */
    const FIELD_CONTENT = 'content';

    /** field obj_id 信箱涉及到的对象Id */
    const FIELD_ID_OBJ = 'obj_id';

    /** field obj_type 对象的类型 */
    const FIELD_OBJ_TYPE = 'obj_type';

    /** field action_type 信箱锁涉及到的操作类型 */
    const FIELD_ACTION_TYPE = 'action_type';

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

    /** field private 是否匿名1,默认否0 */
    const FIELD_PRIVATE = 'private';

    /** 表白墙 */
    const ENUM_OBJ_TYPE_POST = 1;
    /** 卖舍友 */
    const ENUM_OBJ_TYPE_SALE_FRIEND = 2;
    /** 匹配 */
    const ENUM_OBJ_TYPE_MATCH_LOVE = 3;
    /** 评论 */
    const ENUM_OBJ_TYPE_COMMENT = 4;
    /** 赞 */
    const ENUM_OBJ_TYPE_PRAISE = 5;
    /** 聊天 */
    const ENUM_OBJ_TYPE_CHAT = 6;
    /** 悬赏令 */
    const ENUM_OBJ_TYPE_PART_TIME_JOB = 7;

    /** 评论对象 */
    const ENUM_ACTION_TYPE_COMMENT = 1;
    /** 点赞对象 */
    const ENUM_ACTION_TYPE_PRAISE = 2;
    /** 私信 */
    const ENUM_ACTION_TYPE_CHAT = 3;
    /** 悬赏令 */
    const ENUM_ACTION_TYPE_JOB = 4;

    /** 不匿名 */
    const ENUM_NOT_PRIVATE = 0;
    /** 匿名 */
    const ENUM_PRIVATE = 1;

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_FROM,
        self::FIELD_ID_TO,
        self::FIELD_ID_OBJ,
        self::FIELD_CONTENT,
        self::FIELD_OBJ_TYPE,
        self::FIELD_ACTION_TYPE,
        self::FIELD_POST_AT,
        self::FIELD_READ_AT,
        self::CREATED_AT,
        self::FIELD_UPDATED_AT,
        self::FIELD_PRIVATE
    ];

    public function fromUser()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_FROM)->select(User::FIELD_ID,User::FIELD_NICKNAME,User::FIELD_AVATAR,User::FIELD_GENDER);
    }

    public function toUser()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_FROM)->select(User::FIELD_ID,User::FIELD_NICKNAME,User::FIELD_AVATAR,User::FIELD_GENDER);
    }

}