<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/5/27
 * Time: 13:56
 */

namespace App\Models;


class AdminApps extends BaseModel
{
    const TABLE_NAME = 'admin_apps';
    protected $table = self::TABLE_NAME;

    /** field id */
    const FIELD_ID = 'id';

    /** Field admin_id 管理员id */
    const FIELD_ID_ADMIN = 'admin_id';

    /** Field admin_app_id 微信小程序id */
    const FIELD_ID_APP = 'app_id';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_ADMIN,
        self::FIELD_ID_APP
    ];

}