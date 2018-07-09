<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/7/7
 * Time: 17:11
 */

namespace App\Models;


class FormIds extends BaseModel
{
    const TABLE_NAME = 'form_ids';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field user_id */
    const FIELD_ID_USER = 'user_id';

    /** Field form_id */
    const FIELD_ID_FORM = 'form_id';

    /** Field open_id */
    const FIELD_ID_OPEN = 'open_id';

    /** Field expired_at */
    const FIELD_EXPIRED_AT = 'expired_at';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_ID_FORM,
        self::FIELD_EXPIRED_AT,
        self::FIELD_ID_OPEN
    ];
}