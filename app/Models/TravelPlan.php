<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13 0013
 * Time: 9:19
 */

namespace App\Models;


class TravelPlan extends BaseModel
{
    const TABLE_NAME = 'travel_plans';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field user_id */
    const FIELD_ID_USER = 'user_id';

    /** Field title 旅行的目标 */
    const FIELD_TITLE = 'title';

    /** Field distance 旅行的中路程，单位米 */
    const FIELD_DISTANCE = 'distance';

    /** Field status 旅行的状态 */
    const FIELD_STATUS = 'status';

    /** status 旅行的状态，1=旅行中，2=已终止，3=已完成 */
    const ENUM_STATUS_TRAVeLING = 1;
    const ENUM_STATUS_END = 2;
    const ENUM_STATUS_SUCCESS = 3;

    const REL_POINTS = 'points';
    const REL_TRAVEL_LOGS = 'travelLogs';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_TITLE,
        self::FIELD_DISTANCE,
        self::FIELD_STATUS
    ];

    public function points()
    {
        return $this->hasMany(TravelPlanPoint::class,TravelPlanPoint::FIELD_ID_TRAVEL_PLAN,self::FIELD_ID)->orderBy(TravelPlanPoint::FIELD_SORT,'asc');
    }

    public function travelLogs()
    {
        return $this->hasMany(TravelLog::class,TravelLog::FIELD_ID_TRAVEL_PLAN,self::FIELD_ID)->orderBy(TravelLog::FIELD_RUN_AT,'asc');
    }
}