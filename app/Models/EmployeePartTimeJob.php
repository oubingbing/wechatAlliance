<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19 0019
 * Time: 11:13
 */

namespace App\Models;


class EmployeePartTimeJob extends BaseModel
{
    const TABLE_NAME = 'employee_part_time_jobs';
    protected $table = self::TABLE_NAME;

    /** Field id 主键 */
    const FIELD_ID = 'id';

    /** Field part_time_job_id 悬赏ID */
    const FIELD_ID_PART_TIME_JOB = 'part_time_job_id';

    /** Field user_id 赏金猎人ID */
    const FIELD_ID_USER = 'user_id';

    /** Field status 于悬赏的关系状态 */
    const FIELD_STATUS = 'status';

    /** Field score 任务得分 */
    const FIELD_SCORE = 'score';

    /** comments 悬赏评分 */
    const FIELD_COMMENTS = 'comments';

    /** attachments 评论附件 */
    const FIELD_ATTACHMENTS = 'attachments';

    protected $casts = [
        self::FIELD_ATTACHMENTS => 'array',
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_PART_TIME_JOB,
        self::FIELD_ID_USER,
        self::FIELD_STATUS,
        self::FIELD_SCORE,
        self::FIELD_COMMENTS,
        self::FIELD_ATTACHMENTS
    ];

}