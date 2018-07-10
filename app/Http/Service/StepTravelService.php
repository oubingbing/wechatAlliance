<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/10 0010
 * Time: 16:04
 */

namespace App\Http\Service;


use App\Models\RunStep;
use App\Models\User;
use Carbon\Carbon;

class StepTravelService
{
    /**
     * 格式化步数日期
     * 
     * @author yezi
     * 
     * @param $data
     * @return static
     */
    public function formatRunDataToDateTimeString($data)
    {
        $result = collect($data)->map(function ($item){
            $item[RunStep::FIELD_RUN_AT] = Carbon::createFromTimestamp($item['timestamp'])->toDateString();
            return $item;
        });

        return $result;
    }

    /**
     * 用户是否没有步数记录
     * 
     * @author yezi
     * 
     * @param $userId
     * @return mixed
     */
    public function userIfNotStep($userId)
    {
        $result = RunStep::query()->where(RunStep::FIELD_ID_USER,$userId)->value(RunStep::FIELD_ID);
        return $result;
    }

    /**
     * 获取用户最近三十天的步数日期
     * 
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public function getUserThirtyRunData($userId)
    {
        $date = RunStep::query()
            ->where(RunStep::FIELD_ID_USER,$userId)
            ->whereBetween(RunStep::FIELD_RUN_AT,[Carbon::now()->subDay(30),Carbon::now()])
            ->select([RunStep::FIELD_ID,RunStep::FIELD_ID_USER,RunStep::FIELD_STEP,RunStep::FIELD_RUN_AT])
            ->get();
        return $date;
    }

    public function getUserRunDate($data)
    {
        $dates = collect($data)->pluck(RunStep::FIELD_RUN_AT);
        return collect($dates)->toArray();
    }

    public function getUserNewRunStep($userId,$newSteps)
    {
        $checkResult = $this->userIfNotStep($userId);
        if(!$checkResult){
            return $newSteps;
        }

        $oldSteps = $this->getUserThirtyRunData($userId);
        $oldDate = collect($this->getUserRunDate($oldSteps))->map(function ($item){
            $item = Carbon::parse($item)->toDateString();
            return $item;
        });
        $newDate = $this->getUserRunDate($newSteps);

        $diffResult = collect(collect($oldDate)->diff($newDate))->toArray();
        $saveData = [];
        if($diffResult){
            foreach ($newSteps as $step){
                if(in_array($step['timestamp'],$diffResult)){
                    array_push($saveData,$step);
                }
            }
        }

        return $saveData;
    }

    public function saveSteps($userId,$steps)
    {
        $stepArray = [];
        foreach ($steps as $item){
            array_push($stepArray,[
                RunStep::FIELD_ID_USER=>$userId,
                RunStep::FIELD_STEP=>$item['step'],
                RunStep::FIELD_RUN_AT=>$item['run_at'],
                RunStep::FIELD_CREATED_AT=>Carbon::now(),
                RunStep::FIELD_UPDATED_AT=>Carbon::now()
            ]);
        }

        $result = false;
        if(!empty($stepArray)){
            $result = RunStep::insert($stepArray);
        }

        return $result;
    }
}