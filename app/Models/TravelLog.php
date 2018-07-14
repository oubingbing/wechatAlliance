<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13 0013
 * Time: 10:50
 */

namespace App\Models;


class TravelLog extends BaseModel
{
    const TABLE_NAME = 'travel_logs';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field travel_plan_id 旅行计划 */
    const FIELD_ID_TRAVEL_PLAN = 'travel_plan_id';

    /** Field user_id */
    const FIELD_ID_USER = 'user_id';

    /** Field name 抵达点的名字 */
    const FIELD_NAME = 'name';

    /** Field address 抵达点的地址  */
    const FIELD_ADDRESS = 'address';

    /** Field latitude 抵达点地理维度 */
    const FIELD_LATITUDE = 'latitude';

    /** Field longitude  抵达点地理经度 */
    const FIELD_LONGITUDE = 'longitude';

    /** Field distance 里程 */
    const FIELD_DISTANCE = 'distance';

    /** Field step 步数 */
    const FIELD_STEP = 'step';

    /** Field run_at 行程的日期 */
    const FIELD_RUN_AT = 'run_at';

    const REL_PLAN = 'plan';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_TRAVEL_PLAN,
        self::FIELD_ID_USER,
        self::FIELD_ADDRESS,
        self::FIELD_NAME,
        self::FIELD_LATITUDE,
        self::FIELD_LONGITUDE,
        self::FIELD_DISTANCE,
        self::FIELD_STEP,
        self::FIELD_RUN_AT
    ];

    public function plan()
    {
        return $this->belongsTo(TravelPlan::class,self::FIELD_ID_TRAVEL_PLAN,TravelLog::FIELD_ID);
    }

}