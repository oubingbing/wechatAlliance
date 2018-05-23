<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/18
 * Time: 上午10:15
 */

namespace App\Models;


class Friend extends BaseModel
{
    protected $table = 'friends';

    /** field id */
    const FIELD_ID = 'id';

    /** field user_id 用户Id */
    const FIELD_ID_USER = 'user_id';

    /** field friend_id 好友Id */
    const FIELD_ID_FRIEND = 'friend_id';

    /** field nickname 好友昵称 */
    const FIELD_NICKNAME = 'nickname';

    /** field type 好友类型 */
    const FIELD_TYPE = 'type';

    /** field status */
    const FIELD_STATUS = 'status';

    /** field friend_group_id 好友分组Id */
    const FIELD_ID_FRIEND_GROUP = 'friend_group_id';

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';


    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_ID_FRIEND,
        self::FIELD_ID_FRIEND_GROUP,
        self::FIELD_NICKNAME,
        self::FIELD_TYPE,
        self::FIELD_STATUS
    ];

    public function user()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_USER);
    }

    public function friend()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_FRIEND)->select(User::FIELD_ID,User::FIELD_NICKNAME,User::FIELD_AVATAR);
    }

}