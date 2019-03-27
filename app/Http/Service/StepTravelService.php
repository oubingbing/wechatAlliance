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
    private $builder;
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
            ->whereBetween(RunStep::FIELD_RUN_AT,[Carbon::now()->subDay(31),Carbon::now()])
            ->orderBy(RunStep::FIELD_RUN_AT,'asc')
            ->select([RunStep::FIELD_ID,RunStep::FIELD_ID_USER,RunStep::FIELD_STEP,RunStep::FIELD_RUN_AT])
            ->get();
        return $date;
    }

    /**
     * 获取用户所有的步数信息
     *
     * @author yezi
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getUserAllRunData($userId)
    {
        $date = RunStep::query()
            ->where(RunStep::FIELD_ID_USER,$userId)
            ->where(RunStep::FIELD_STATUS,RunStep::ENUM_STATUS_CAN_USE)
            //->whereBetween(RunStep::FIELD_RUN_AT,[Carbon::now()->subDay(31),Carbon::now()->subDay(1)])
            ->orderBy(RunStep::FIELD_RUN_AT,'asc')
            ->get();
        return $date;
    }

    /**
     * 只获取步数的日期
     *
     * @author yezi
     *
     * @param $data
     * @return array
     */
    public function getUserRunDate($data)
    {
        $dates = collect($data)->pluck(RunStep::FIELD_RUN_AT);
        return collect($dates)->toArray();
    }

    /**
     * 获取用户最新的步数信息
     *
     * @author yezi
     *
     * @param $userId
     * @param $newSteps
     * @return array
     */
    public function getUserNewRunStep($userId,$newSteps)
    {
        $checkResult = $this->userIfNotStep($userId);
        if(!$checkResult){
            return $newSteps;
        }

        $oldSteps = $this->getUserThirtyRunData($userId);
        $oldDate  = collect($this->getUserRunDate($oldSteps))->map(function ($item){
            $item = Carbon::parse($item)->toDateString();
            return $item;
        });

        $newDate    = $this->getUserRunDate($newSteps);
        $diffResult = collect(collect($newDate)->diff($oldDate))->all();
        $saveData   = [];
        if($diffResult){
            foreach ($newSteps as $step){
                if(in_array($step['run_at'],$diffResult)){
                    array_push($saveData,$step);
                }
            }
        }

        return $saveData;
    }

    /**
     * 保存用户步数信息
     *
     * @author yezi
     *
     * @param $userId
     * @param $steps
     * @return bool
     */
    public function saveSteps($userId,$steps)
    {
        $stepArray = [];
        foreach ($steps as $item){
            array_push($stepArray,[
                RunStep::FIELD_ID_USER     => $userId,
                RunStep::FIELD_STEP        => $item['step'],
                RunStep::FIELD_RUN_AT      => $item['run_at'],
                RunStep::FIELD_CREATED_AT  => Carbon::now(),
                RunStep::FIELD_UPDATED_AT  => Carbon::now(),
                RunStep::FIELD_TYPE        => $item['run_at'] == Carbon::now()->toDateString()?RunStep::ENUM_TYPE_TODAY:RunStep::ENUM_TYPE_NOT_TODAY
            ]);
        }

        $result = false;
        if(!empty($stepArray)){
            $result = RunStep::insert($stepArray);
        }

        return $result;
    }

    /**
     * 判断用户当天是否有记录数据
     *
     * @author yezi
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function ifRunDataInToday($userId)
    {
        $result = RunStep::query()->where(RunStep::FIELD_ID_USER,$userId)->where(RunStep::FIELD_RUN_AT,Carbon::now()->toDateString())->first();
        return $result;
    }

    /**
     * 更新用户当天的步数
     *
     * @author yezi
     *
     * @param $userId
     * @param $runData
     */
    public function updateTodayRunData($userId,$runData)
    {
        $todayRunData = '';
        foreach ($runData as $item){
            if($item[RunStep::FIELD_RUN_AT] == Carbon::now()->toDateString()){
                $todayRunData = $item;
                break;
            }
        }

        if($todayRunData){
            //更新数据
            $step = RunStep::query()->where(RunStep::FIELD_ID_USER,$userId)->where(RunStep::FIELD_RUN_AT,Carbon::now()->toDateString())->first();
            if($step){
                $step->{RunStep::FIELD_STEP} = $todayRunData['step'];
                $step->save();
            }
        }
    }

    /**
     * 更新以往当天的数据
     *
     * @author yezi
     *
     * @param $userId
     * @param $runData
     */
    public function updateTypeIsTodayRunData($userId,$runData)
    {
        $date = RunStep::query()
            ->where(RunStep::FIELD_ID_USER,$userId)
            ->whereBetween(RunStep::FIELD_RUN_AT,[Carbon::now()->subDay(31),Carbon::now()])
            ->where(RunStep::FIELD_TYPE,RunStep::ENUM_TYPE_TODAY)
            ->pluck(RunStep::FIELD_RUN_AT);
        $date = collect($date)->map(function ($item){
            return Carbon::parse($item)->toDateString();
        });
        $date = collect($date)->toArray();
        foreach ($runData as $item){
            if(in_array($item[RunStep::FIELD_RUN_AT],$date)){
                RunStep::query()
                    ->where(RunStep::FIELD_ID_USER,$userId)
                    ->where(RunStep::FIELD_RUN_AT,$item[RunStep::FIELD_RUN_AT])
                    ->update([RunStep::FIELD_STEP=>$item['step'],RunStep::FIELD_TYPE=>RunStep::ENUM_TYPE_NOT_TODAY]);
            }
        }
    }

    /**
     * 获取用户当天的步数
     *
     * @author yezi
     *
     * @param $userId
     * @return mixed
     */
    public function todayStep($userId)
    {
        $step = RunStep::query()->where(RunStep::FIELD_ID_USER,$userId)->where(RunStep::FIELD_RUN_AT,Carbon::now()->toDateString())->value(RunStep::FIELD_STEP);
        return $step;
    }

    /**
     * 统计用户全部步数
     *
     * @author yezi
     *
     * @param $userId
     * @return mixed
     */
    public function statisticStep($userId)
    {
        $total = RunStep::query()->where(RunStep::FIELD_ID_USER,$userId)->sum(RunStep::FIELD_STEP);
        return $total;
    }

    /**
     * 构建查询构造器
     *
     * @author yezi
     *
     * @param $userId
     * @return $this
     */
    public function stepBuilder($userId=null)
    {
        $builder = RunStep::query();
        if($userId){
            $builder->where(RunStep::FIELD_ID_USER,$userId);
        }
        $this->builder = $builder;

        return $this;
    }

    public function selectToday()
    {
        $this->builder->whereBetween(RunStep::FIELD_RUN_AT,[Carbon::now()->startOfDay(),Carbon::now()->endOfDay()]);
        return $this;
    }

    public function filterByApp($user)
    {
        $this->builder->whereHas(RunStep::REL_USER,function ($query)use($user){
            $query->where(User::FIELD_ID_APP,$user->{User::FIELD_ID_APP});
        });
        return $this;
    }

    /**
     * 排序
     *
     * @author yezi
     *
     * @param $orderBy
     * @param $sortBy
     * @return $this
     */
    public function sort($orderBy,$sortBy)
    {
        $this->builder->orderBy($orderBy,$sortBy);
        return $this;
    }

    /**
     * 返回查询构造器
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
     * 格式化数据
     *
     * @author yezi
     *
     * @param $step
     * @return mixed
     */
    public function formatStep($step)
    {
        $step->{RunStep::FIELD_RUN_AT} = Carbon::parse($step->{RunStep::FIELD_RUN_AT})->toDateString();

        return $step;
    }

    public function canTravelRunData($userId)
    {
        $steps = RunStep::query()
            ->where(RunStep::FIELD_ID_USER,$userId)
            ->where(RunStep::FIELD_STATUS,RunStep::ENUM_STATUS_CAN_USE)
            ->where(RunStep::FIELD_RUN_AT,'!=',Carbon::now()->toDateString())
            ->orderBy(RunStep::FIELD_RUN_AT,'asc')
            ->get();

        return $steps;
    }
}