<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/24
 * Time: 下午8:04
 */

namespace App\Http\Controllers\Wechat;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\FollowService;
use App\Models\Follow;

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

    /**
     * 关注
     *
     * @author yezi
     *
     * @return mixed
     */
    public function followUser()
    {
        $user   = request()->input('user');
        $obj    = request()->input('obj_id');
        if(!$obj){
            throw new ApiException("关注参数不能为空",5000);
        }

        $follow = $this->follow->userFollow($user->id,$obj);
        return $follow;
    }

    public function getFollow()
    {
        $user   = request()->input('user');
        $obj    = request()->input('obj_id');
        if(!$obj){
            throw new ApiException("参数不能为空",5000);
        }

        $result = $this->follow->checkFollow($user->id,$obj,Follow::ENUM_OBJ_TYPE_USER);
        if($result){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 获取
     *
     * @author yezi
     *
     * @return mixed
     */
    public function followUserPage()
    {
        $user       = request()->input('user');
        $pageSize   = request()->input('page_size',10);
        $pageNumber = request()->input('page_number',1);
        $orderBy    = request()->input('order_by','created_at');
        $sortBy     = request()->input('sort_by','desc');
        $userId     = request()->input('user_id',0);
        $type     = request()->input('type');

        if(!$userId){
            $userId = $user->id;
        }

        $pageParams = ['page_size'=>$pageSize, 'page_number'=>$pageNumber];
        $query      = $this->follow->query($userId,$type)->sort($orderBy,$sortBy)->done();
        $selectData = [
            Follow::FIELD_ID,
            Follow::FIELD_ID_USER,
            Follow::FIELD_ID_OBJ,
            Follow::FIELD_OBJ_TYPE,
            Follow::FIELD_FOLLOW_NICKNAME,
            Follow::FIELD_FOLLOW_AVATAR,
            Follow::FIELD_BE_FOLLOW_NICKNAME,
            Follow::FIELD_BE_FOLLOW_AVATAR,
            Follow::FIELD_CREATED_AT,
        ];

        $saleFriends = paginate($query,$pageParams, $selectData,function($item){
            return $this->follow->formatSingle($item);
        });

        return $saleFriends;
    }


}