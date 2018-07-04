<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/13
 * Time: 下午2:56
 */

namespace App\Models;


class Post extends BaseModel
{
    protected $table = 'posts';

    /** field id 主键 */
    const FIELD_ID = 'id';

    /** field poster_id 发帖人Id */
    const FIELD_ID_POSTER = 'poster_id';

    /** field college_id 学校Id */
    const FIELD_ID_COLLEGE = 'college_id';

    /** field content 内容 */
    const FIELD_CONTENT = 'content';

    /** field attachments 贴子的附件,例如图片之类的 */
    const FIELD_ATTACHMENTS = 'attachments';

    /** field topic 贴子的主题 */
    const FIELD_TOPIC = 'topic';

    /** field type 贴子的类型 */
    const FIELD_TYPE = 'type';

    /** field status */
    const FIELD_STATUS = 'status';

    /** field private 是否匿名 */
    const FIELD_PRIVATE = 'private';

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';

    /** field praise_number */
    const FIELD_PRAISE_NUMBER = 'praise_number';

    /** field comment_number */
    const FIELD_COMMENT_NUMBER = 'comment_number';

    /** 不匿名 */
    const ENUM_NOT_PRIVATE = 0;
    /** 匿名 */
    const ENUM_PRIVATE = 1;

    const REL_USER = 'poster';
    const REL_MESSAGE_SESSION = 'messageSession';

    protected $casts = [
        self::FIELD_ATTACHMENTS => 'array',
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_POSTER,
        self::FIELD_ID_COLLEGE,
        self::FIELD_CONTENT,
        self::FIELD_TOPIC,
        self::FIELD_ATTACHMENTS,
        self::FIELD_TYPE,
        self::FIELD_STATUS,
        self::FIELD_PRIVATE,
        self::CREATED_AT,
        self::FIELD_UPDATED_AT,
        self::FIELD_PRAISE_NUMBER,
        self::FIELD_COMMENT_NUMBER
    ];

    /**
     * 发帖人
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function poster()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_POSTER);
    }

    /**
     * 所属大学
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function college()
    {
        return $this->belongsTo(Colleges::class,self::FIELD_ID_COLLEGE);
    }

    /**
     * 点赞人
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function praises()
    {
        return $this->hasMany(Praise::class,Praise::FIELD_ID_OBJ,self::FIELD_ID)->where(Praise::FIELD_OBJ_TYPE,Praise::ENUM_OBJ_TYPE_POST);
    }

    /**
     * 贴子评论
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class,Comment::FIELD_ID_OBJ,self::FIELD_ID)->where(Comment::FIELD_OBJ_TYPE,Comment::ENUM_OBJ_TYPE_POST);
    }

    public function follows()
    {
        return $this->hasMany(Follow::class,Follow::FIELD_ID_OBJ)->where(Follow::FIELD_OBJ_TYPE,Follow::ENUM_OBJ_TYPE_POST);
    }

    public function messageSession()
    {
        return $this->hasOne(MessageSession::class,MessageSession::FIELD_OBJ_ID,self::FIELD_ID)->where(MessageSession::FIELD_OBJ_TYPE,MessageSession::ENUM_OBJ_TYPE_POST);
    }

}