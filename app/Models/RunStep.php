<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/10 0010
 * Time: 16:31
 */

namespace App\Models;


class RunStep extends BaseModel
{
    const TABLE_NAME = 'run_steps';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field user_id */
    const FIELD_ID_USER = 'user_id';

    /** Field step */
    const FIELD_STEP = 'step';

    /** Field run_at */
    const FIELD_RUN_AT = 'run_at';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_STEP,
        self::FIELD_RUN_AT
    ];
}