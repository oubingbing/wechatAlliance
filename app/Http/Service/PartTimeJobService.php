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

class PartTimeJobService
{
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
    public function saveEmployeeParTimeJob($employeeId,$partTimeJobId,$status)
    {
        $result = EmployeePartTimeJob::create([
            EmployeePartTimeJob::FIELD_ID_PART_TIME_JOB=>$partTimeJobId,
            EmployeePartTimeJob::FIELD_ID_USER=>$employeeId,
            EmployeePartTimeJob::FIELD_STATUS=>$status
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
        $result = $job->save();

        return $result;
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

}