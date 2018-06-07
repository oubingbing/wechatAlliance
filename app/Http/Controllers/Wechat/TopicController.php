<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/6 0006
 * Time: 17:48
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\User;

class TopicController extends Controller
{
    /**
     * 获取话题
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function topic()
    {
        $user = request()->input('user');

        $topic = Topic::query()
            ->where(Topic::FIELD_ID_APP,$user->{User::FIELD_ID_APP})
            ->where(Topic::FIELD_STATUS,Topic::ENUM_STATUS_UP)
            ->orderBy(Topic::FIELD_CREATED_AT,'DESC')
            ->first();

        return $topic;
    }

    public function topicDetail($id)
    {
        $user = request()->input('user');

        $topic = Topic::query()->find($id);
        if(!$topic){
            throw new ApiException('话题不存在',500);
        }

        return $topic;
    }

}