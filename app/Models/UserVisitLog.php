<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2018/3/18
 * Time: 下午5:54
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserVisitLog extends Model
{
    const TABLE_NAME = 'user_visit_logs';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field user_id */
    const FIELD_ID_USER = 'user_id';

    /** Field nickname */
    const FIELD_NICKNAME = 'nickname';

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';

    const REL_USER = 'user';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_NICKNAME,
        self::FIELD_CREATED_AT,
        self::FIELD_UPDATED_AT,
        self::FIELD_DELETED_AT
    ];

    public function user()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_USER,User::FIELD_ID);
    }
}