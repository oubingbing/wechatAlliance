<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19 0019
 * Time: 10:17
 */

namespace App\Models;


class PartTimeJob extends BaseModel
{
    const TABLE_NAME = 'part_time_jobs';
    protected $table = self::TABLE_NAME;

    /** field id 主键 */
    const FIELD_ID = 'id';

    /** Field boss_id 悬赏人 */
    const FIELD_ID_BOSS = 'user_id';

    /** Field title 兼职 */
    const FIELD_TITLE = 'title';

    /** Field content 兼职内容 */
    const FIELD_CONTENT = 'content';

    /** Field attachments 附件 */
    const FIELD_ATTACHMENTS = 'attachments';

    /** Field salary 悬赏酬劳 */
    const FIELD_SALARY = 'salary';

    /** Field status 悬赏的状态 */
    const FIELD_STATUS = 'status';

    /** Field type */
    const FIELD_TYPE = 'type';

    /** Field end_at 兼职的是小日期 */
    const FIELD_END_AT = 'end_at';

    /** field status 悬赏的状态 1=悬赏中，2=任务中，3=悬赏终止，4=悬赏完成 */
    const ENUM_STATUS_RECRUITING = 1;
    const ENUM_STATUS_WORKING = 2;
    const ENUM_STATUS_END = 3;
    const ENUM_STATUS_SUCCESS = 4;

    const REL_USER = 'user';
    const REL_EMPLOYEE_JOB = 'employeeJob';
    const REL_COMMENT = 'comment';
    const REL_FIRE_EMPLOYEE_JOB = 'fireEmployeeJob';

    protected $casts = [
        self::FIELD_ATTACHMENTS => 'array',
    ];

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_BOSS,
        self::FIELD_TITLE,
        self::FIELD_CONTENT,
        self::FIELD_ATTACHMENTS,
        self::FIELD_SALARY,
        self::FIELD_STATUS,
        self::FIELD_TYPE,
        self::FIELD_END_AT
    ];

    /**
     * 发帖人
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,self::FIELD_ID_BOSS);
    }

    /**
     * 发帖人
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employeeJob()
    {
        $result = $this->hasOne(EmployeePartTimeJob::class,EmployeePartTimeJob::FIELD_ID_PART_TIME_JOB,self::FIELD_ID)
            ->where(EmployeePartTimeJob::FIELD_STATUS,'!=',EmployeePartTimeJob::ENUM_STATUS_BE_FIRED);

        return $result;
    }

    /**
     * 获取最新一条被炒的人员
     *
     * @author yez
     *
     * @return $this
     */
    public function fireEmployeeJob()
    {
        $result = $this->hasOne(EmployeePartTimeJob::class,EmployeePartTimeJob::FIELD_ID_PART_TIME_JOB,self::FIELD_ID)
            ->where(EmployeePartTimeJob::FIELD_STATUS,EmployeePartTimeJob::ENUM_STATUS_BE_FIRED)
            ->orderBy(EmployeePartTimeJob::FIELD_CREATED_AT,'DESC');

        return $result;
    }

    /**
     * 悬赏令的任务评论
     *
     * @author yezi
     *
     * @return $this
     */
    public function comment()
    {
        $result = $this->hasOne(Comment::class,Comment::FIELD_ID_OBJ,self::FIELD_ID)->where(Comment::FIELD_OBJ_TYPE,Comment::ENUM_OBJ_TYPE_JOB);
        return $result;
    }

}