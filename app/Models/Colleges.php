<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/8
 * Time: 下午8:26
 */

namespace App\Models;


class Colleges extends BaseModel
{

    protected $table = 'colleges';

    /** field id */
    const FIELD_ID = 'id';

    /** field name 大学名字 */
    const FIELD_NAME = 'name';

    /** field type 学校类型 */
    const FIELD_TYPE = 'type';

    /** field properties 学校属性 */
    const FIELD_PROPERTIES = 'properties';

    /** field province 省份 */
    const FIELD_PROVINCE = 'province';

    /** field city 城市 */
    const FIELD_CITY = 'city';

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_NAME,
        self::FIELD_TYPE,
        self::FIELD_PROVINCE,
        self::FIELD_CITY,
        self::FIELD_PROPERTIES,
        self::CREATED_AT,
        self::FIELD_UPDATED_AT
    ];


}