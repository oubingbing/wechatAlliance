<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/13 0013
 * Time: 11:00
 */

namespace App\Models;


class TravelLogPoi extends BaseModel
{
    const TABLE_NAME = 'travel_log_pois';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field travel_log_id 所属旅行日志 */
    const FIELD_ID_TRAVEL_ID = 'travel_log_id';

    /** Field title 周边的名字，例如酒店名字，景点名字 */
    const FIELD_TITLE = 'title';

    /** Field address 周边的地址 */
    const FIELD_ADDRESS = 'address';

    /** Field type poi的类型，1=酒店，2=美食，3=景点 */
    const FIELD_TYPE = 'type';

    /** poi的类型，1=酒店，2=美食，3=景点 */
    const ENUM_TYPE_HOTEL = 1;
    const ENUM_TYPE_FOOD = 2;
    const ENUM_TYPE_VIEW_SPOT = 3;

    const REL_TRAVEL_LOG = 'travelLog';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_TRAVEL_ID,
        self::FIELD_TITLE,
        self::FIELD_ADDRESS,
        self::FIELD_TYPE
    ];

    public function travelLog()
    {
        return $this->belongsTo(TravelLog::class,self::FIELD_ID_TRAVEL_ID,TravelLog::FIELD_ID);
    }

}