<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/10
 * Time: 10:43
 */

namespace App\Models;


class BlackList extends BaseModel
{
    protected $table = 'black_list';

    /** field id */
    const FIELD_ID = 'id';

    /** Field user_id **/
    const FIELD_ID_USER = 'user_id';

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER
    ];
}