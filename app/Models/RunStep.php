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

    /** Field type 是否是当天的数据，后期获取需要更新 */
    const FIELD_TYPE = 'type';

    /** Field step */
    const FIELD_STEP = 'step';

    /** Field status 是否已使用，1=未使用，2=已使用 */
    const FIELD_STATUS = 'status';

    /** Field run_at */
    const FIELD_RUN_AT = 'run_at';

    /** status 是否已使用，1=未使用，2=已使用 */
    const ENUM_STATUS_CAN_USE = 1;
    const ENUM_STATUS_BE_USE = 2;

    /** 是否是当天的数据，1=不是，2=是 */
    const ENUM_TYPE_NOT_TODAY = 1;
    const ENUM_TYPE_TODAY = 2;

    const REL_USER = 'user';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_STEP,
        self::FIELD_RUN_AT,
        self::FIELD_STATUS,
    ];

    public function user()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_USER,User::FIELD_ID);
    }
}