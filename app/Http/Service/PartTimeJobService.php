<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/19 0019
 * Time: 11:24
 */

namespace App\Http\Service;


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

}