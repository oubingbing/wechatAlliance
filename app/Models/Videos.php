<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2019/3/3
 * Time: 13:29
 */

namespace App\Models;


class Videos extends BaseModel
{
    protected $table = 'videos';

    /** field id 主键 */
    const FIELD_ID = 'id';

    /** Field app_id **/
    const FIELD_ID_APP = 'app_id';

    /** Field v_id **/
    const FIELD_V_ID = 'v_id';

    /** Field attachments **/
    const FIELD_ATTACHMENTS = 'attachments';

    /** Field title 标题 **/
    const FIELD_TITLE = 'title';

    /** Field introduction **/
    const FIELD_INTRODUCTION = 'introduction';

    /** Field sort 序号 **/
    const FIELD_SORT = 'sort';

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';

    protected $casts = [
        self::FIELD_ATTACHMENTS => 'array'
    ];

    protected $fillable = [
        self::FIELD_ATTACHMENTS,
        self::FIELD_V_ID,
        self::FIELD_ID_APP,
        self::FIELD_TITLE,
        self::FIELD_SORT,
        self::FIELD_INTRODUCTION,
        self::FIELD_CREATED_AT,
        self::FIELD_DELETED_AT,
        self::FIELD_UPDATED_AT
    ];

}