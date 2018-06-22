<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19 0019
 * Time: 10:17
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\PartTimeJobService;
use App\Models\EmployeePartTimeJob;
use App\Models\PartTimeJob;
use Illuminate\Support\Facades\Request;

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
        $user = $request->input('user');
        $title = $request->input('title');
        $content = $request->input('content');
        $attachments = $request->input('attachments',[]);
        $salary = $request->input('salary',0);
        $endAt = $request->input('end_at');

        $valid = $this->partTimeJob->validParam($request);
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
     * 接单
     *
     * @author yezi
     *
     * @return mixed
     * @throws ApiException
     */
    public function receiptOrder()
    {
        $user = request()->input('user');
        $orderId = request()->input('id');

        if(!$orderId){
            throw new ApiException('悬赏令不能为空!',500);
        }

        $parTimeJob = $this->partTimeJob->getPartTimeJobById($orderId);
        if(!$parTimeJob){
            throw new ApiException('悬赏不存在！',500);
        }

        if($parTimeJob != PartTimeJob::ENUM_STATUS_RECRUITING){
            throw new ApiException('该悬赏令不处于悬赏中！',500);
        }

        $employeePartTimeJob = $this->partTimeJob->getEmployeeJobByUserIdAndJobId($user->id,$orderId);
        if($employeePartTimeJob){
            throw new ApiException('您已接过该悬赏令，不能重复接单！',500);
        }

        $status = EmployeePartTimeJob::ENUM_STATUS_WORKING;
        $result = $this->partTimeJob->saveEmployeeParTimeJob($user->id,$orderId,$status);
        if(!$result){
            throw new ApiException('接单失败！',500);
        }

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
    public function commentPartTimeJob()
    {
        $user = request()->input('user');
        $id = request()->input('id');
        $employeeId = request()->input('employeeId');
        $score = request()->input('score');
        $comment = request()->input('comments');
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

        $result = $this->partTimeJob->commentJob($employeeId,$id,$score,$comment,$attachments);

        return $result;
    }

    public function finishJob()
    {
        $user = request()->input('user');
        $id = request()->input('id');
        $employeeId = request()->input('employee_id');

        if(is_null($id)){
            throw new ApiException('悬赏令不能为空！',500);
        }

        if(is_null($employeeId)){
            throw new ApiException('赏金猎人不能为空！',500);
        }

        $job = $this->getPartTimeJobById($id);
        if(!$job){
            throw new ApiException('悬赏令不存在！',500);
        }

        if($job->{PartTimeJob::FIELD_ID_BOSS} != $user->id){
            throw new ApiException('您不是该悬赏令的发布者！',500);
        }

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
            throw new ApiException($exception);
        }

        return $finishPartTimeResult;
    }

}