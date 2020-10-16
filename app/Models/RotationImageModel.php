<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/16 0016
 * Time: 16:43
 */

namespace App\Models;


class RotationImageModel extends BaseModel
{
    const TABLE_NAME = 'rotation_images';
    protected $table = self::TABLE_NAME;

    /** field id 主键 */
    const FIELD_ID = 'id';

    /** field app_id */
    const FIELD_ID_APP = 'app_id';

    /** field college_id 学校Id */
    const FIELD_ID_COLLEGE = 'college_id';

    /** field url **/
    const FIELD_URL = "url";

    /** field wechat_app **/
    const FIELD_WECHAT_APP = "wechat_app";

    /** field status **/
    const FIELD_STATUS = "status";

    /** field sort **/
    const FIELD_SORT = "sort";

    protected $fillable = [
        self::FIELD_ID_APP,
        self::FIELD_ID_COLLEGE,
        self::FIELD_URL,
        self::FIELD_STATUS,
        self::FIELD_SORT,
        self::FIELD_WECHAT_APP
    ];
}