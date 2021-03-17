<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/24
 * Time: 下午8:04
 */

namespace App\Http\Controllers\Wechat;


use App\Http\Controllers\Controller;
use App\Http\Service\FollowService;

class FollowController extends Controller
{
    protected $follow;

    public function __construct(FollowService $followLogic)
    {
        $this->follow = $followLogic;
    }

    /**
     * 关注
     *
     * @author yezi
     *
     * @return mixed
     */
    public function contact()
    {
        $user   = request()->input('user');
        $obj    = request()->input('obj_id');
        $type   = request()->input('obj_type');
        $follow = $this->follow->follow($user->id,$obj,$type);

        return $follow;
    }

    /**
     * 取消关注
     *
     * @author yezi
     *
     * @param $id
     * @param $type
     *
     * @return array
     */
    public function cancelFollow($id,$type)
    {
        $user    = request()->input('user');
        $objId   = $id;
        $objType = $type;
        $result  =  $this->follow->cancelFollow($user->id,$objId,$objType);

        return collect($result)->toArray();
    }

}