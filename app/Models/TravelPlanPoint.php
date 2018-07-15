<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13 0013
 * Time: 9:23
 */

namespace App\Models;


class TravelPlanPoint extends BaseModel
{
    const TABLE_NAME = 'travel_plan_points';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field travel_plan_id 旅行计划的ID */
    const FIELD_ID_TRAVEL_PLAN = 'travel_plan_id';

    /** Field name 站点的名字 */
    const FIELD_NAME = 'name';

    /** Field address 站点地址 */
    const FIELD_ADDRESS = 'address';

    /** Field latitude 纬度 */
    const FIELD_LATITUDE = 'latitude';

    /** Field longitude 经度 */
    const FIELD_LONGITUDE = 'longitude';

    /** Field sort 顺序 */
    const FIELD_SORT = 'sort';

    /** Field type 站点的类型 1=起点，2=途径站点，3=终点 */
    const FIELD_TYPE = 'type';

    /** Field status 是否经过站点，1=未抵达，2=已抵达，3=用户已走出站点范围 */
    const FIELD_STATUS = 'status';

    /** Field status 是否经过站点，1=未抵达，2=已抵达，3=用户已走出站点范围 */
    const ENUM_STATUS_NOT_ARRIVE = 1;
    const ENUM_STATUS_ARRIVE = 2;
    const ENUM_STATUS_OVERRIDE = 3;

    /** type 站点的类型 1=起点，2=途径站点，3=终点 */
    const ENUM_TYPE_START_POINT = 1;
    const ENUM_TYPE_ROUTE_POINT = 2;
    const ENUM_TYPE_END_POINT = 3;

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_TRAVEL_PLAN,
        self::FIELD_NAME,
        self::FIELD_ADDRESS,
        self::FIELD_LATITUDE,
        self::FIELD_LONGITUDE,
        self::FIELD_SORT,
        self::FIELD_TYPE,
        self::FIELD_STATUS
    ];
}