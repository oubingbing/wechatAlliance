<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/25 0025
 * Time: 17:52
 */

namespace App\Models;


class TemplateLog extends BaseModel
{
    const TABLE_NAME = 'template_logs';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field app_id */
    const FIELD_ID_APP = 'app_id';

    /** Field open_id */
    const FIELD_ID_OPEN = 'open_id';

    /** Field template_id */
    const FIELD_ID_TEMPLATE = 'template_id';

    /** Field content */
    const FIELD_CONTENT = 'content';

    /** Field page */
    const FIELD_PAGE = 'page';

    /** Field status */
    const FIELD_STATUS = 'status';

    /** Field type */
    const FIELD_TYPE = 'type';

    /** Field result 返回结果 */
    const FIELD_RESULT = 'result';

    /** 发送状态 status 1=成功，2=失败 */
    const ENUM_STATUS_SUCCESS = 1;
    const ENUM_STATUS_FAIL = 2;

    protected $casts = [
        self::FIELD_CONTENT => 'array',
        self::FIELD_RESULT => 'array'
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_APP,
        self::FIELD_ID_OPEN,
        self::FIELD_ID_TEMPLATE,
        self::FIELD_CONTENT,
        self::FIELD_PAGE,
        self::FIELD_RESULT
    ];

}