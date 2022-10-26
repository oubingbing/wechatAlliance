<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/24
 * Time: 下午4:54
 */

namespace App\Models;


class Follow extends BaseModel
{
    protected $table = 'follows';

    /** Field id */
    const FIELD_ID = 'id';

    /** Field user_id 关注人Id */
    const FIELD_ID_USER = 'user_id';

    /** Field obj_id 关注对象的Id */
    const FIELD_ID_OBJ = 'obj_id';

    /** Field obj_type 关注对象的类型 */
    const FIELD_OBJ_TYPE = 'obj_type';

    /** Field status 关注的状态 */
    const FIELD_STATUS = 'status';

    /** Field follow_nickname 关注的状态 */
    const FIELD_FOLLOW_NICKNAME = 'follow_nickname';

    /** Field follow_avatar 关注的状态 */
    const FIELD_FOLLOW_AVATAR = 'follow_avatar';

    /** Field be_follow_nickname 关注的状态 */
    const FIELD_BE_FOLLOW_NICKNAME = 'be_follow_nickname';

    /** Field be_follow_avatar 关注的状态 */
    const FIELD_BE_FOLLOW_AVATAR = 'be_follow_avatar';

    /** 关注状态-已关注 */
    const ENUM_STATUS_FOLLOW = 1;
    /** 关注状态-取消关注 */
    const ENUM_STATUS_CANCEL_FOLLOW = 2;

    /** obj_type-评论表白墙 */
    const ENUM_OBJ_TYPE_POST = 1;
    /** obj_type-评论卖舍友 */
    const ENUM_OBJ_TYPE_SALE_FRIEND = 2;
    /** obj_type 评论暗恋匹配 */
    const ENUM_OBJ_TYPE_MATCH_LOVE = 3;
    /** obj_type 评论 */
    const ENUM_OBJ_TYPE_COMMENT = 4;
    /** obj_type 用户 */
    const ENUM_OBJ_TYPE_USER = 5;

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_ID_OBJ,
        self::FIELD_OBJ_TYPE,
        self::FIELD_STATUS,
        self::FIELD_FOLLOW_NICKNAME,
        self::FIELD_FOLLOW_AVATAR,
        self::FIELD_BE_FOLLOW_NICKNAME,
        self::FIELD_BE_FOLLOW_AVATAR,
    ];

}