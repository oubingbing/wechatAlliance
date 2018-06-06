<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/6 0006
 * Time: 17:30
 */

namespace App\Http\Service;


use App\Models\Topic;

class TopicService
{
    /**
     * 更新话题的状态
     *
     * @author yezi
     *
     * @param $topicId
     * @param $status
     * @return bool
     */
    public function updateStatus($userId,$topicId,$status)
    {
        $topic = Topic::query()->where(Topic::FIELD_ID,$topicId)->where(Topic::FIELD_ID_USER,$userId)->first();
        if(!$topic){
            return false;
        }

        $topic->{Topic::FIELD_STATUS} = $status;
        $topic->save();

        return true;
    }

}