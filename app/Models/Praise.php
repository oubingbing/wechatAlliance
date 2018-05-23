<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/13
 * Time: 下午2:58
 */

namespace App\Models;


class Praise extends BaseModel
{
    protected $table = 'praises';
    
    /** field id */
    const FIELD_ID = 'id';
    
    /** field owner_id 点赞人 */
    const FIELD_ID_OWNER = 'owner_id';
    
    /** field obj_id 被点赞对象Id */
    const FIELD_ID_OBJ = 'obj_id';
    
    /** field obj_type 被点赞对象的类型 */
    const FIELD_OBJ_TYPE = 'obj_type';

    /** field college_id 学校 */
    const FIELD_ID_COLLEGE = 'college_id';

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';

    /** type - 表白点赞 */
    const ENUM_OBJ_TYPE_POST = 1;
    /** type - 卖舍友点赞 */
    const ENUM_OBJ_TYPE_SALE_FRIEND = 2;
    /** type - 暗恋匹配点赞 */
    const ENUM_OBJ_TYPE_MATCH_LOVE = 3;
    /** type - 评论 */
    const ENUM_OBJ_TYPE_COMMENT = 4;
    
    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_COLLEGE,
        self::FIELD_ID_OWNER,
        self::FIELD_ID_OBJ,
        self::FIELD_OBJ_TYPE,
        self::FIELD_CREATED_AT,
        self::FIELD_UPDATED_AT
    ];

    public function user()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_OWNER,User::FIELD_ID);
    }
}