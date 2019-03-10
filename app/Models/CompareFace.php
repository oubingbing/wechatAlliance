<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5 0005
 * Time: 9:33
 */

namespace App\Models;


class CompareFace extends BaseModel
{
    const TABLE_NAME = 'compare_faces';
    protected $table = self::TABLE_NAME;

    /** Field id */
    const FIELD_ID = 'id';

    /** Field user_id 用户ID */
    const FIELD_ID_USER = 'user_id';

    /** Field attachments 比对的图片 */
    const FIELD_ATTACHMENTS = 'attachments';

    /** Field confidence 对比的相似度 */
    const FIELD_CONFIDENCE = 'confidence';

    /** Field status 对比的状态 1=成功，2=失败 */
    const FIELD_STATUS = 'status';

    /** Field rect_a 比对结果 */
    const FIELD_COMPARE_RESULT = 'compare_result';

    /** status 比对成功 status=1 */
    const ENUM_STATUS_SUCCESS = 1;

    /** status 比对失败 status=2 */
    const ENUM_STATUS_FAIL = 2;

    const REL_POSTER = 'poster';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_ATTACHMENTS,
        self::FIELD_CONFIDENCE,
        self::FIELD_STATUS,
        self::FIELD_COMPARE_RESULT
    ];

    protected $casts = [
        self::FIELD_ATTACHMENTS => 'array',
        self::FIELD_COMPARE_RESULT => 'array',
    ];

    /**
     * 发帖人
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function poster()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_USER);
    }
}