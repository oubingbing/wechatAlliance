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

    /** Field name 抵达点的名字 */
    const FIELD_NAME = 'name';

    /** Field address 抵达点的地址  */
    const FIELD_ADDRESS = 'address';

    /** Field latitude 抵达点地理维度 */
    const FIELD_LATITUDE = 'latitude';

    /** Field longitude  抵达点地理经度 */
    const FIELD_LONGITUDE = 'longitude';

}