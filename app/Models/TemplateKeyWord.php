<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/25 0025
 * Time: 10:49
 */

namespace App\Models;


class TemplateKeyWord extends BaseModel
{
    const TABLE_NAME = 'template_key_words';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field keyword */
    const FIELD_KEY_WORD = 'keyword';

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
        self::FIELD_TITLE,
        self::FIELD_KEY_WORD,
        self::FIELD_KEY_WORD_IDS,
        self::FIELD_CONTENT
    ];

}