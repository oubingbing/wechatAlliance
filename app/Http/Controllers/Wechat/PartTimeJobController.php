<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19 0019
 * Time: 10:17
 */

namespace App\Http\Controllers\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\CommentService;
use App\Http\Service\PaginateService;
use App\Http\Service\PartTimeJobService;
use App\Http\Service\UserService;
use App\Models\Comment;
use App\Models\EmployeePartTimeJob;
use App\Models\Inbox;
use App\Models\PartTimeJob;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PartTimeJobController extends Controller
{
    private $partTimeJob;

    public function __construct(PartTimeJobService $partTimeJobService)
    {
        $this->partTimeJob = $partTimeJobService;
    }

    /**
     * 发布悬赏令
     * 
     * @author yezi
     * 
     * @param Request $request
     * @return mixed
     * @throws ApiException
     */
    public function store(Request $request)
    {
        $user        = $request->input('user');
        $title       = $request->input('title');
        $content     = $request->input('content');
        $attachments = $request->input('attachments',[]);
        $salary      = $request->input('salary',0);
        $endAt       = $request->input('end_at',null);

        $valid       = $this->partTimeJob->validParam($request);
        if(!$valid['valid']){
            throw new ApiException($valid['message'],500);
        }
        
        $result = $this->partTimeJob->savePartTimeJob($user->id,$title,$content,$attachments,$salary,$endAt);
        if(!$result){
            throw new ApiException('发布失败！',500);
        }
        
        return $result;
    }

    /**
     * 领取悬赏令
     *
     * @author yezi
     *
     * @return mixed
     * @throws ApiException
     */
    public function receiptOrder()
    {
        $user    = request()->input('user');
        $orderId = request()->input('id');

        if(!$orderId){
            throw new ApiException('悬赏令不能为空!',500);
        }

        $parTimeJob = $this->partTimeJob->getPartTimeJobById($orderId);
        if(!$parTimeJob){
            throw new ApiException('悬赏不存在！',500);
        }

        if($parTimeJob->{PartTimeJob::FIELD_ID_BOSS} == $user->id){
            throw new ApiException('不能接自己的悬赏令！',500);
        }

        if($parTimeJob->{PartTimeJob::FIELD_STATUS} != PartTimeJob::ENUM_STATUS_RECRUITING){
            throw new ApiException('该悬赏令不处于悬赏中！',500);
        }

        $employeePartTimeJob = $this->partTimeJob->getEmployeeJobByUserIdAndJobId($user->id,$orderId);
        if($employeePartTimeJob){
            throw new ApiException('您已接过该悬赏令，不能重复接单！',500);
        }

        try{
            \DB::beginTransaction();

            $status = EmployeePartTimeJob::ENUM_STATUS_WORKING;
            $result = $this->partTimeJob->saveEmployeeParTimeJob($user->id,$orderId,$status);
            if(!$result){
                throw new ApiException('接单失败！',500);
            }

            $updateResult = $this->partTimeJob->updatePartTimeJobStatusById($orderId,PartTimeJob::ENUM_STATUS_WORKING);
            if(!$updateResult){
                throw new ApiException('接单失败！',500);
            }

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new ApiException($exception,500);
        }

        $boss            = $parTimeJob->{PartTimeJob::REL_USER};
        $employeeProfile = $user->{User::REL_PROFILE};

        //给悬赏人发送模板消息
        $title  = '任务接收通知';
        $values = [$parTimeJob->{PartTimeJob::FIELD_TITLE},$employeeProfile->{UserProfile::FIELD_NAME},"您的悬赏令【{$parTimeJob->{PartTimeJob::FIELD_TITLE}}】已被揭下,详情请登录小程序查看。"];
        senTemplateMessage($user->{User::FIELD_ID_APP},$boss->id,$title,$values);

        //给赏金猎人发送模板消息
        $title  = '任务接收通知';
        $values = [$parTimeJob->{PartTimeJob::FIELD_TITLE},$employeeProfile->{UserProfile::FIELD_NAME},"您的揭下了【{$boss->{User::REL_PROFILE}->{UserProfile::FIELD_NAME}}】的悬赏令【{$parTimeJob->{PartTimeJob::FIELD_TITLE}}】,详情请登录小程序查看。"];
        senTemplateMessage($user->{User::FIELD_ID_APP},$user->id,$title,$values);

        //给悬赏人的消息盒子头发信息
        senInbox($user->{User::FIELD_ID_APP},$user->id, $parTimeJob->{PartTimeJob::FIELD_ID_BOSS}, $parTimeJob->id, '您的悬赏令被揭下！', Inbox::ENUM_OBJ_TYPE_PART_TIME_JOB, Inbox::ENUM_ACTION_TYPE_JOB, Carbon::now());

        //给悬赏人发送短信通知
        $content = "【情书网】您好，{$employeeProfile->{UserProfile::FIELD_NAME}}接了您的悬赏令：{$parTimeJob->{PartTimeJob::FIELD_TITLE}}，详情请登陆小程序查看。";
        sendMessage($user->{User::FIELD_ID_APP},$boss->{User::FIELD_MOBILE},$content);

        return $result;
    }

    /**
     * 评论任务
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static
     * @throws ApiException
     */
    public function commentPartTimeJob($id)
    {
        $user        = request()->input('user');
        $score       = request()->input('score');
        $content     = request()->input('content');
        $attachments = request()->input('attachments');

        if(!$score){
            throw new ApiException('任务评分不能为空！',500);
        }

        $partTimeJob = $this->partTimeJob->getPartTimeJobById($id);
        if(!$partTimeJob){
            throw new ApiException('悬赏令不存在！',500);
        }

        if($user->id != $partTimeJob->{PartTimeJob::FIELD_ID_BOSS}){
            throw new ApiException('你不是该悬赏令的发布者！',500);
        }

        try{
            \DB::beginTransaction();

            $result  = $this->partTimeJob->commentJob($id,$score);
            $content = empty($content)?'无':$content;
            app(CommentService::class)->saveComment($user->id, $partTimeJob->id, $content, Comment::ENUM_OBJ_TYPE_JOB, null, $attachments);

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new ApiException($exception,500);
        }

        $commentScore = collect(['1'=>'好评', '2'=>'中评', '3'=>'差评'])->get((string)$score);
        $employeeJob  = $partTimeJob->{PartTimeJob::REL_EMPLOYEE_JOB};
        $employee     = $employeeJob->{EmployeePartTimeJob::REL_USER};
        //给赏金猎人发送模板消息
        $title = '评价完成通知';
        $values = [$partTimeJob->{PartTimeJob::FIELD_TITLE},$commentScore,'悬赏人对您的任务进行了评价，详情请登录小程序查看！'];
        senTemplateMessage($user->{User::FIELD_ID_APP},$employee->id,$title,$values);

        //给赏金猎人人的消息盒子头发信息
        senInbox($user->{User::FIELD_ID_APP},$user->id, $employee->id, $partTimeJob->id, '悬赏人对您的任务进行了评价！', Inbox::ENUM_OBJ_TYPE_PART_TIME_JOB, Inbox::ENUM_ACTION_TYPE_JOB, Carbon::now());

        return $result;
    }

    /**
     * 确认完成任务
     *
     * @author yezi
     *
     * @return bool
     * @throws ApiException
     */
    public function finishJob($id)
    {
        $user = request()->input('user');

        if(is_null($id)){
            throw new ApiException('悬赏令不能为空！',500);
        }

        $job = $this->partTimeJob->getPartTimeJobById($id);
        if(!$job){
            throw new ApiException('悬赏令不存在！',500);
        }

        if($job->{PartTimeJob::FIELD_STATUS} != PartTimeJob::ENUM_STATUS_WORKING){
            throw new ApiException('悬赏令状态错误！',500);
        }

        if($job->{PartTimeJob::FIELD_ID_BOSS} != $user->id){
            throw new ApiException('您不是该悬赏令的发布者！',500);
        }

        $employee = $job->{PartTimeJob::REL_EMPLOYEE_JOB};
        $employeeUser = $employee->{EmployeePartTimeJob::REL_USER};
        $employeeId = $employee->{EmployeePartTimeJob::FIELD_ID_USER};

        try{
            \DB::beginTransaction();

            $finishPartTimeResult = $this->partTimeJob->finishPartTimeJob($id);
            if(!$finishPartTimeResult){
                throw new ApiException('确认失败！',500);
            }

            $finishJobResult = $this->partTimeJob->finishJob($id,$employeeId);
            if(!$finishJobResult){
                throw new ApiException('确认失败',500);
            }

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new ApiException($exception,500);
        }

        $result = $this->detail($id);

        //给悬赏人发送模板消息
        $title  = '任务完成通知';
        $values = [$job->{PartTimeJob::FIELD_TITLE},'该悬赏令已被您确认完成,详情请登录小程序查看',date('Y-m-d H:i:s',time())];
        senTemplateMessage($user->{User::FIELD_ID_APP},$user->id,$title,$values);

        //给上架猎人发送模板消息
        $title  = '任务完成通知';
        $values = [$job->{PartTimeJob::FIELD_TITLE},"该悬赏令已被悬赏人确认完成,详情请登录小程序查看。",date('Y-m-d H:i:s',time())];
        senTemplateMessage($user->{User::FIELD_ID_APP},$employeeUser->id,$title,$values);

        //给赏金猎人人的消息盒子头发信息
        senInbox($user->{User::FIELD_ID_APP},$user->id, $employeeUser->id, $job->id, '悬赏人确认了您完成了悬赏令！', Inbox::ENUM_OBJ_TYPE_PART_TIME_JOB, Inbox::ENUM_ACTION_TYPE_JOB, Carbon::now());

        return $result;
    }

    /**
     * 获取悬赏令列表
     *
     * @author yezi
     *
     * @return mixed
     */
    public function partTimJobs()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $orderBy    = request()->input('order_by', 'created_at');
        $sortBy     = request()->input('sort_by', 'desc');
        $status     = request()->input('type');
        $filter     = request()->input('filter');

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $query      = $this->partTimeJob->builder($user,$status)->filter($filter)->sort($orderBy,$sortBy)->done();
        $jobs       = paginate($query, $pageParams, '*', function ($item) use ($user) {

            return $this->partTimeJob->formatSinglePost($item, $user);

        });

        return $jobs;
    }

    /**
     * 获取最新的悬赏
     *
     * @author yezi
     *
     * @return static
     */
    public function getMostNew()
    {
        $user   = request()->input('user');
        $time   = request()->input('time');

        $result = app(PartTimeJobService::class)->newList($user,$time);
        $result = collect($result)->map(function ($item)use($user){
            return $this->partTimeJob->formatSinglePost($item, $user);
        });

        return $result;
    }

    /**
     * 悬赏令详情
     *
     * @author yezi
     *
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        $user       = request()->input('user');
        $job        = $this->partTimeJob->getPartTimeJobById($id);
        $job->{PartTimeJob::REL_USER};
        $employee   = $job->{PartTimeJob::REL_EMPLOYEE_JOB};

        $userService              = app(UserService::class);
        $job->boss_profile        = $userService->getProfileById($job->{PartTimeJob::FIELD_ID_BOSS});
        $job->boss_profile->phone = $userService->getPhoneById($job->{PartTimeJob::FIELD_ID_BOSS});
        $job->employee_profile    = '';
        $jobStatus                = '';
        if($employee){
            $job->employee_profile        = $userService->getProfileById($employee->{EmployeePartTimeJob::FIELD_ID_USER});
            $job->employee_profile->phone = $userService->getPhoneById($employee->{EmployeePartTimeJob::FIELD_ID_USER});
            $jobStatus                    = $this->partTimeJob->countEmployee($employee->{EmployeePartTimeJob::FIELD_ID_USER});
        }

        $result = $this->partTimeJob->formatSinglePost($job,$user);

        $result['comment'] = $this->partTimeJob->getJobComment($job->id);
        if($job->{PartTimeJob::FIELD_STATUS} == PartTimeJob::ENUM_STATUS_SUCCESS && empty($result['comment'])){
            $result['can_comment'] = true;
        }else{
            $result['can_comment'] = false;
        }

        $result['job_status'] = $jobStatus;

        return $result;
    }

    /**
     * 解雇赏金猎人，重新发布悬赏
     *
     * @author yezi
     *
     * @return null|static[]
     * @throws ApiException
     */
    public function restartJob($id)
    {
        $user  = request()->input('user');
        $jobId = $id;

        $job = $this->partTimeJob->getPartTimeJobById($jobId);
        if(!$job){
            throw new ApiException('悬赏令不存在！',500);
        }

        if($job->{PartTimeJob::FIELD_ID_BOSS} != $user->id){
            throw new ApiException('您不是悬赏人！',500);
        }

        try{
            \DB::beginTransaction();

            $result = $this->partTimeJob->fireEmployee($jobId);

            \DB::commit();
        }catch (\Exception $exception){
            \DB::rollBack();
            throw new ApiException($exception,500);
        }

        $employeeJod  = $job->{PartTimeJob::REL_FIRE_EMPLOYEE_JOB};
        $employee     = $employeeJod->{EmployeePartTimeJob::REL_USER};
        $employeeName = $employee->{User::REL_PROFILE}->{UserProfile::FIELD_NAME};

        //给悬赏人发送模板消息
        $bossTitle    = '订单终止提醒';
        $bossValues   = [$job->id,date('Y-m-d H:i:s',time()),$user->{User::REL_PROFILE}->{UserProfile::FIELD_NAME},"您终止了与【{$employeeName}】的悬赏关系后重新发布了悬赏令,详情请登录小程序查看。"];//订单编号、终止时间、终止人、温馨提示
        senTemplateMessage($user->{User::FIELD_ID_APP},$user->id,$bossTitle,$bossValues);

        //给赏金猎人发送模板消息
        $title = '订单终止提醒';
        $employeeValues = [$job->id,date('Y-m-d H:i:s',time()),$user->{User::REL_PROFILE}->{UserProfile::FIELD_NAME},"【{$user->{User::REL_PROFILE}->{UserProfile::FIELD_NAME}}】终止了与您的悬赏关系,详情请登录小程序查看。"];//订单编号、终止时间、终止人、温馨提示
        senTemplateMessage($user->{User::FIELD_ID_APP},$employee->id,$title,$employeeValues);

        //给赏金猎人人的消息盒子头发信息
        senInbox($user->{User::FIELD_ID_APP},$user->id, $employee->id, $job->id, '你被悬赏人解雇了，悬赏人重新发布了悬赏令！', Inbox::ENUM_OBJ_TYPE_PART_TIME_JOB, Inbox::ENUM_ACTION_TYPE_JOB, Carbon::now());

        return $result;
    }

    /**
     * 获取赏金猎人的悬赏记录
     *
     * @author yezi
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function employeeJobComments($id)
    {
        $user = request()->input('user');
    }

    /**
     * 赏金猎人的悬赏记录
     *
     * @author yezi
     *
     * @param $jobId
     * @return mixed
     * @throws ApiException
     */
    public function missionRecord($jobId)
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size', 10);
        $pageNumber = request()->input('page_number', 1);
        $status     = request()->input('status');

        $job = $this->partTimeJob->getPartTimeJobById($jobId);
        if(!$job){
            throw new ApiException('悬赏令不存在！',500);
        }

        $employee = $job->{PartTimeJob::REL_EMPLOYEE_JOB};
        if(!$employee){
            throw new ApiException('赏金猎人不存在！',500);
        }

        $pageParams = ['page_size' => $pageSize, 'page_number' => $pageNumber];
        $query      = $this->partTimeJob->employeeMissionComments($employee->{EmployeePartTimeJob::FIELD_ID_USER},$status);
        $jobs       = app(PaginateService::class)->paginate($query, $pageParams, '*', function ($item) use ($user) {
            return $item;
        });

        return $jobs;
    }

    /**
     * 赏金猎人放弃任务
     *
     * @author yezi
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null|static
     * @throws ApiException
     */
    public function stopMission($id)
    {
        $user = request()->input('user');

        $job = PartTimeJob::query()
            ->where(PartTimeJob::FIELD_ID,$id)
            ->where(PartTimeJob::FIELD_ID_BOSS,$user->id)
            ->where(PartTimeJob::FIELD_STATUS,PartTimeJob::ENUM_STATUS_RECRUITING)
            ->first();
        if(!$job){
            throw new ApiException('无法终止任务！',500);
        }

        $job->{PartTimeJob::FIELD_STATUS} = PartTimeJob::ENUM_STATUS_END;
        $result = $job->save();
        if(!$result){
            throw new ApiException('终止失败！',500);
        }

        //给悬赏人发送模板消息
        $title  = '订单终止提醒';
        $values = [$job->id,date('Y-m-d H:i:s',time()),$user->{User::REL_PROFILE}->{UserProfile::FIELD_NAME},"您终止了悬令【{$job->{PartTimeJob::FIELD_TITLE}}】,详情请登录小程序查看。"];//订单编号、终止时间、终止人、温馨提示
        senTemplateMessage($user->{User::FIELD_ID_APP},$user->id,$title,$values);

        return $job;
    }

    /**
     * 删除悬赏令,这里有点问题
     *
     * @author yezi
     *
     * @param $id
     * @return mixed
     * @throws ApiException
     */
    public function delete($id)
    {
        $user   = request()->input('user');

        $result = PartTimeJob::where(PartTimeJob::FIELD_ID, $id)->delete();
        if(!$result){
            throw new ApiException('删除失败！',500);
        }

        return $result;
    }

}