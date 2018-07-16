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

    /** Field province 省份 */
    const FIELD_PROVINCE = 'province';

    /** Field city 城市 */
    const FIELD_CITY = 'city';

    /** Field district 县 */
    const FIELD_DISTRICT = 'district';

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

    /** Field point_id 所属站点 */
    const FIELD_ID_POINT = 'point_id';


    /** Field point_distance 地图坐标的距离 */
    const FIELD_LENGTH = 'length';

    /** Field total_distance 总的地图坐标的距离 */
    const FIELD_TOTAL_LENGTH = 'total_length';

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
        self::FIELD_RUN_AT,
        self::FIELD_ID_POINT,
        self::FIELD_LENGTH,
        self::FIELD_TOTAL_LENGTH,
        self::FIELD_PROVINCE,
        self::FIELD_CITY,
        self::FIELD_DISTRICT
    ];

    public function plan()
    {
        return $this->belongsTo(TravelPlan::class,self::FIELD_ID_TRAVEL_PLAN,TravelLog::FIELD_ID);
    }

}