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

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_TITLE,
        self::FIELD_DISTANCE
    ];
}