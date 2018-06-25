<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/25 0025
 * Time: 15:45
 */

namespace App\Models;


class WeChatTemplate extends BaseModel
{
    const TABLE_NAME = 'templates';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field app_id */
    const FIELD_ID_APP = 'app_id';

    /** Field template_id */
    const FIELD_ID_TEMPLATE = 'template_id';

    /** Field title */
    const FIELD_TITLE = 'title';

    /** Field content */
    const FIELD_CONTENT = 'content';

    /** Field keyword_ids */
    const FIELD_KEY_WORD_IDS = 'keyword_ids';

    protected $casts = [
        self::FIELD_KEY_WORD_IDS => 'array',
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_APP,
        self::FIELD_TITLE,
        self::FIELD_ID_TEMPLATE,
        self::FIELD_CONTENT,
        self::FIELD_KEY_WORD_IDS
    ];
}