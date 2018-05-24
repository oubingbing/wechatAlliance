<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/27
 * Time: 上午10:55
 */

namespace App\Models;


class MatchLove extends BaseModel
{
    protected $table = 'match_loves';

    /** field id */
    const FIELD_ID = 'id';

    /** field owner_id 所属人Id */
    const FIELD_ID_OWNER = 'owner_id';

    /** field college_id 学校 */
    const FIELD_ID_COLLEGE = 'college_id';

    /** field user_name 匹配人 */
    const FIELD_USER_NAME = 'user_name';

    /** field match_name 被匹配人 */
    const FIELD_MATCH_NAME = 'match_name';

    /** field content 匹配成功后对方可以看到你对ta说的话 */
    const FIELD_CONTENT = 'content';

    /** field private 是否匿名,默认否 */
    const FIELD_PRIVATE = 'private';

    /** field is_password 其他人查看匹配的名字是否需要密码,默认是要 */
    const FIELD_IS_PASSWORD = 'is_password';

    /** field password 密码 */
    const FIELD_PASSWORD = 'password';

    /** field attachments 附件 */
    const FIELD_ATTACHMENTS = 'attachments';

    /** field comment_number 评论数 */
    const FIELD_COMMENT_NUMBER = 'comment_number';

    /** field praise_number 点赞数 */
    const FIELD_PRAISE_NUMBER = 'praise_number';

    /** field type */
    const FIELD_TYPE = 'type';

    /** field status */
    const FIELD_STATUS = 'status';

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';

    /** 不匿名 */
    const ENUM_NOT_PRIVATE = 0;
    /** 匿名 */
    const ENUM_PRIVATE = 1;

    /** 需要密码 */
    const ENUM_PROVIDE_PASSWORD = 1;
    /** 不需要密码 */
    const ENUM_NOT_PASSWORD = 2;

    /** 匹配中 */
    const ENUM_STATUS_MATCHING = 1;
    /** 匹配成功 */
    const ENUM_STATUS_SUCCESS = 2;

    const REL_USER = 'user';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_OWNER,
        self::FIELD_ID_COLLEGE,
        self::FIELD_USER_NAME,
        self::FIELD_MATCH_NAME,
        self::FIELD_CONTENT,
        self::FIELD_PRIVATE,
        self::FIELD_IS_PASSWORD,
        self::FIELD_PASSWORD,
        self::FIELD_COMMENT_NUMBER,
        self::FIELD_ATTACHMENTS,
        self::FIELD_PRAISE_NUMBER,
        self::FIELD_TYPE,
        self::FIELD_STATUS
    ];

    public function user()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_OWNER)->select([
            User::FIELD_ID,
            User::FIELD_NICKNAME,
            User::FIELD_AVATAR,
            User::FIELD_GENDER
        ]);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class,Follow::FIELD_ID_OBJ)->where(Follow::FIELD_OBJ_TYPE,Follow::ENUM_OBJ_TYPE_MATCH_LOVE);
    }

}