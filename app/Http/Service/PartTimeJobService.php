<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19 0019
 * Time: 11:24
 */

namespace App\Http\Service;


use App\Exceptions\ApiException;
use App\Models\EmployeePartTimeJob;
use App\Models\PartTimeJob;
use App\Models\User;

class PartTimeJobService
{
    private $builder;

    /**
     * 新建悬赏
     *
     * @author yezi
     *
     * @param $userId
     * @param $title
     * @param $content
     * @param $attachments
     * @param $salary
     * @param $endAt
     * @return mixed
     */
    public function savePartTimeJob($userId,$title,$content,$attachments,$salary,$endAt)
    {
        $result = PartTimeJob::create([
            PartTimeJob::FIELD_ID_BOSS=>$userId,
            PartTimeJob::FIELD_TITLE=>$title,
            PartTimeJob::FIELD_CONTENT=>$content,
            PartTimeJob::FIELD_ATTACHMENTS=>$attachments,
            PartTimeJob::FIELD_SALARY=>$salary,
            PartTimeJob::FIELD_END_AT=>$endAt
        ]);

        return $result;
    }

    /**
     * 根据主键更新状态
     *
     * @author yezi
     *
     * @param $id
     * @param $status
     * @return int
     */
    public function updatePartTimeJobStatusById($id,$status)
    {
        $result = PartTimeJob::query()->where(PartTimeJob::FIELD_ID,$id)->update([PartTimeJob::FIELD_STATUS=>$status]);

        return $result;
    }

    /**
     * 验证参数
     *
     * @author yezi
     *
     * @param $request
     * @return array
     */
    public function validParam($request)
    {
        $rules = [
            'title' => 'required',
            'content' => 'required',
            'salary' => 'sometimes | numeric',
            'end_at' => 'sometimes | date'
        ];
        $message = [
            'title.required' => '标题不能为空！',
            'content.required' => '内容不能为空！',
            'salary.numeric' => '酬劳必须是数字！',
            'end_at.required' => '日期格式错误！'
        ];
        $validator = \Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['valid'=>false,'message'=>$errors->first()];
        }else{
            return ['valid'=>true,'message'=>'success'];
        }
    }

    /**
     * 获取悬赏令
     *
     * @author yezi
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getPartTimeJobById($id)
    {
        $result = PartTimeJob::query()->find($id);

        return $result;
    }

    /**
     * 获取某个用户的悬赏令
     *
     * @author yezi
     *
     * @param $userId
     * @param $partTimeJobId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getEmployeeJobByUserIdAndJobId($userId,$partTimeJobId)
    {
        $result = EmployeePartTimeJob::query()
            ->where(EmployeePartTimeJob::FIELD_ID_USER,$userId)
            ->where(EmployeePartTimeJob::FIELD_ID_PART_TIME_JOB,$partTimeJobId)
            ->first();

        return $result;
    }

    public function getEmployeeJobByJobId($partTimeJobId,$userId)
    {
        $result = EmployeePartTimeJob::query()
            ->where(EmployeePartTimeJob::FIELD_ID_PART_TIME_JOB,$partTimeJobId)
            ->where(EmployeePartTimeJob::FIELD_ID_USER,$userId)
            ->first();

        return $result;
    }

    /**
     * 用户接单
     *
     * @author yezi
     *
     * @param $employeeId
     * @param $partTimeJobId
     * @param $status
     * @return mixed
     */
    public function saveEmployeeParTimeJob($employeeId,$partTimeJobId,$status,$attachments=[])
    {
        $result = EmployeePartTimeJob::create([
            EmployeePartTimeJob::FIELD_ID_PART_TIME_JOB=>$partTimeJobId,
            EmployeePartTimeJob::FIELD_ID_USER=>$employeeId,
            EmployeePartTimeJob::FIELD_STATUS=>$status,
            EmployeePartTimeJob::FIELD_ATTACHMENTS=>$attachments
        ]);

        return $result;
    }

    /**
     * 对人物进行评分
     *
     * @author yezi
     *
     * @param $employeeId
     * @param $jobId
     * @param $score
     * @param $comment
     * @param $attachments
     * @return \Illuminate\Database\Eloquent\Model|null|static
     * @throws ApiException
     */
    public function commentJob($employeeId,$jobId,$score,$comment,$attachments)
    {
        $employeeJob = $this->getEmployeeJobByJobId($jobId,$employeeId);
        if(!$employeeJob){
            throw new ApiException('任务不存在！',500);
        }

        $employeeJob->{EmployeePartTimeJob::FIELD_SCORE} = $score;
        $employeeJob->{EmployeePartTimeJob::FIELD_COMMENTS} = $comment;
        $employeeJob->{EmployeePartTimeJob::FIELD_ATTACHMENTS} = $attachments;
        $result = $employeeJob->save();
        if(!$result){
            throw new ApiException('评分失败！',500);
        }

        return $employeeJob;
    }

    /**
     * 完成悬赏令
     *
     * @author yezi
     *
     * @param $id
     * @return bool
     */
    public function finishPartTimeJob($id)
    {
        $job = $this->getPartTimeJobById($id);

        $job->{PartTimeJob::FIELD_STATUS} = PartTimeJob::ENUM_STATUS_SUCCESS;
        $job->save();

        return $job;
    }

    /**
     * 完成任务
     *
     * @author yezi
     *
     * @param $id
     * @param $employeeId
     * @return bool
     * @throws ApiException
     */
    public function finishJob($id,$employeeId)
    {
        $job = $this->getEmployeeJobByJobId($id,$employeeId);
        if(!$job){
            throw new ApiException('任务不存在！',500);
        }

        $job->{EmployeePartTimeJob::FIELD_STATUS} = EmployeePartTimeJob::ENUM_STATUS_SUCCESS;
        $result = $job->save();

        return $result;
    }

    /**
     * 获取最新帖子
     *
     * @author yezi
     *
     * @param $user
     * @param $time
     * @return mixed
     */
    public function newList($user,$time)
    {
        $result = PartTimeJob::query()
            ->with([PartTimeJob::REL_USER=>function($query){
                $query->select(User::FIELD_ID,User::FIELD_NICKNAME,User::FIELD_AVATAR,User::FIELD_GENDER);
            }])
            ->whereHas(PartTimeJob::REL_USER,function ($query)use($user){
                $query->where(User::FIELD_ID_APP,$user->{User::FIELD_ID_APP});
            })
            ->when($time, function ($query) use ($time) {
                return $query->where(PartTimeJob::FIELD_CREATED_AT, '>=', $time);
            })
            ->get();

        return $result;
    }

    /**
     * 构造查询语句
     *
     * @author yezi
     *
     * @param $user
     * @param int $status
     * @return $this
     */
    public function builder($user,$status)
    {
        $this->builder = PartTimeJob::query()
            ->with([PartTimeJob::REL_USER=>function($query){
                $query->select(User::FIELD_ID,User::FIELD_NICKNAME,User::FIELD_AVATAR,User::FIELD_GENDER);
            },PartTimeJob::REL_EMPLOYEE=>function($query){
                $query->select(EmployeePartTimeJob::FIELD_ID,EmployeePartTimeJob::FIELD_ID_USER,EmployeePartTimeJob::FIELD_ID_PART_TIME_JOB,EmployeePartTimeJob::FIELD_STATUS);
            }])
            ->whereHas(PartTimeJob::REL_USER,function ($query)use($user){
                $query->where(User::FIELD_ID_APP,$user->{User::FIELD_ID_APP});
            })
            ->when(in_array($status,[PartTimeJob::ENUM_STATUS_RECRUITING,PartTimeJob::ENUM_STATUS_WORKING,PartTimeJob::ENUM_STATUS_END,PartTimeJob::ENUM_STATUS_SUCCESS]),function ($query)use($status){
                return $query->where(PartTimeJob::FIELD_STATUS,$status);
            });

        if($status == 6){
            $this->builder->where(PartTimeJob::FIELD_ID_BOSS,$user->id);
        }

        return $this;
    }

    /**
     * 过滤查询
     *
     * @author yezi
     *
     * @param string $title
     * @return $this
     */
    public function filter($title='')
    {
        $this->builder->when($title,function ($query)use($title){
            return $query->where(PartTimeJob::FIELD_TITLE,'ilike',"%$title%");
        });

        return $this;
    }

    /**
     * 排序
     *
     * @author yezi
     *
     * @param $orderBy
     * @param $sort
     * @return $this
     */
    public function sort($orderBy,$sort)
    {
        $this->builder->orderBy($orderBy,$sort);

        return $this;
    }

    /**
     * 返回查询构建的语句
     *
     * @author yezi
     *
     * @return mixed
     */
    public function done()
    {
        return $this->builder;
    }

    /**
     * 格式化单挑数据
     *
     * @author yezi
     *
     * @param $job
     * @param $user
     * @return mixed
     */
    public function formatSinglePost($job,$user)
    {
        $job->can_entry = false;
        $job->can_delete = false;
        $job->can_restart = false;
        $job->can_comfirm = false;
        $job->can_comment = false;
        $job->show_contact = false;
        $job->show_employee_tip = '';
        $job->can_show_tip = false;
        $job->give_up = false;
        $job->role = '';
        if($job->{PartTimeJob::FIELD_ID_BOSS} == $user->id){
            $job->can_entry = true;
            $job->can_delete = true;
            $job->role = 'boss';
            if($job->{PartTimeJob::FIELD_STATUS} == PartTimeJob::ENUM_STATUS_WORKING){
                $job->can_comfirm = true;
                $job->can_restart = true;
            }
            if($job->{PartTimeJob::FIELD_STATUS} == PartTimeJob::ENUM_STATUS_SUCCESS){
                $job->can_comment = true;
            }
        }

        if($job->{PartTimeJob::REL_EMPLOYEE}){
            if($job->{PartTimeJob::REL_EMPLOYEE}->{EmployeePartTimeJob::FIELD_ID_USER} == $user->id){
                $job->show_contact = true;
                $job->can_entry = true;
                $job->role = 'employee';
                switch ($job->{PartTimeJob::FIELD_STATUS}){
                    case PartTimeJob::ENUM_STATUS_WORKING:
                        $job->can_show_tip = true;
                        $job->show_employee_tip = '任务中';
                        $job->give_up = true;
                        break;
                    case PartTimeJob::ENUM_STATUS_END:
                        $job->can_show_tip = true;
                        $job->show_employee_tip = '任务终止';
                        break;
                    case PartTimeJob::ENUM_STATUS_SUCCESS:
                        $job->can_show_tip = true;
                        $job->show_employee_tip = '任务完成';
                        break;
                }
            }
        }

        return $job;
    }

}