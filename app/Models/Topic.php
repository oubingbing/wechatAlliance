<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/6 0006
 * Time: 10:02
 */

namespace App\Models;


class Topic extends BaseModel
{
    const TABLE_NAME = 'topics';
    protected $table = self::TABLE_NAME;

    /** field id 用户Id */
    const FIELD_ID = 'id';

    /** Field user_id 发表人，可以使后台管理员和用户 */
    const FIELD_ID_USER = 'user_id';

    /** Field app_id */
    const FIELD_ID_APP = 'app_id';

    /** Field user_type 发帖人类型，1=后台管理员，2=用户 */
    const FIELD_USER_TYPE = 'user_type';

    /** Field title 话题的标题 */
    const FIELD_TITLE = 'title';

    /** Field content 话题内容 */
    const FIELD_CONTENT = 'content';

    /** Field attachments 话题附件 */
    const FIELD_ATTACHMENTS = 'attachments';

    /** Field praise_number 点赞人数 */
    const FIELD_PRAISE_NUMBER = 'praise_number';

    /** Field view_number 流浪人数 */
    const FIELD_VIEW_NUMBER = 'view_number';

    /** Field comment_number 评论人数 */
    const FIELD_COMMENT_NUMBER = 'comment_number';

    /** Field status 状态 */
    const FIELD_STATUS = 'status';

    /** 后台管理员 */
    const ENUM_USER_TYPE_ADMIN = 1;
    /** 用户 */
    const ENUM_USER_TYPE_WE_CHAT_USER = 2;

    /** 话题上架 */
    const ENUM_STATUS_UP = 2;
    /** 话题下架 */
    const ENUM_STATUS_DOWN = 1;

    protected $casts = [
        self::FIELD_ATTACHMENTS => 'array',
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_ID_APP,
        self::FIELD_USER_TYPE,
        self::FIELD_TITLE,
        self::FIELD_CONTENT,
        self::FIELD_ATTACHMENTS,
        self::FIELD_PRAISE_NUMBER,
        self::FIELD_VIEW_NUMBER,
        self::FIELD_COMMENT_NUMBER,
        self::FIELD_STATUS
    ];

    /**
     * 贴子评论
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class,Comment::FIELD_ID_OBJ,self::FIELD_ID)->where(Comment::FIELD_OBJ_TYPE,Comment::ENUM_OBJ_TYPE_TOPIC);
    }

}