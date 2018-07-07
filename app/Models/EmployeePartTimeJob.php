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

    /** Field status 与悬赏的关系状态 */
    const FIELD_STATUS = 'status';

    /** Field score 任务得分 */
    const FIELD_SCORE = 'score';

    /** 用户执行悬赏令的状态 1=执行任务中，2=被雇主不信任解除雇佣关系,3=任务完成,4=猎人自动放弃任务 */
    const ENUM_STATUS_WORKING = 1;
    const ENUM_STATUS_BE_FIRED = 2;
    const ENUM_STATUS_SUCCESS = 3;
    const ENUM_STATUS_ABANDON = 4;

    /** 任务评分，1=好评，2=中评，3=差评 */
    const ENUM_SCORE_GOOD = 1;
    const ENUM_SCORE_MIDDLE = 2;
    const ENUM_SCORE_BAD = 3;

    const REL_USER = 'user';
    const REL_JOB = 'job';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_PART_TIME_JOB,
        self::FIELD_ID_USER,
        self::FIELD_STATUS,
        self::FIELD_SCORE
    ];

    /**
     * 赏金猎人
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_USER,User::FIELD_ID);
    }

    /**
     * 悬赏令
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(PartTimeJob::class,self::FIELD_ID_PART_TIME_JOB,PartTimeJob::FIELD_ID)->withTrashed();
    }

}