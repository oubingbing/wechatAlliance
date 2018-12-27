<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21 0021
 * Time: 16:14
 */

namespace App\Models;


class QiNiuTokenModel extends BaseModel
{
    const TABLE_NAME = 'qiniu_tokens';
    protected $table = self::TABLE_NAME;

    const FIELD_ID = 'id';

    const FIELD_TOKEN = 'token';

    const FIELD_EXPIRED_AT = 'expired_at';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_TOKEN,
        self::FIELD_EXPIRED_AT
    ];
}