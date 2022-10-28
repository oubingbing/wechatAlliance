<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/17
 * Time: 下午4:37
 */

namespace App\Http\Controllers\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\InboxService;
use App\Http\Service\PraiseService;
use App\Http\Service\UserService;
use App\Models\Inbox;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class PraiseController extends Controller
{
    protected $inbox;
    protected $praise;

    public function __construct(InboxService $inboxLogic,PraiseService $praiseLogic)
    {
        $this->inbox  = $inboxLogic;
        $this->praise = $praiseLogic;
    }

    /**
     * 新增点赞
     *
     * @author yezi
     *
     * @return mixed
     * @throws ApiException
     */
    public function store()
    {
        $user       = request()->input('user');
        $ownerId    = $user->{User::FIELD_ID};
        $objId      = request()->input('obj_id');
        $objType    = request()->input('obj_type');
        $collegeId  = $user->{User::FIELD_ID_COLLEGE};

        $objUserId  = $this->praise->getObjUserId($objType,$objId);
        if(!$objUserId){
            throw new ApiException('对象不存在',404);
        }

        $history = $this->praise->checkRepeat($user->id,$objId,$objType);
        if($history){
            return;
        }

        $fromId     = $user->id;
        $toId       = $objUserId;
        $content    = '有新的点赞';
        $postAt     = Carbon::now();
        $actionType = Inbox::ENUM_ACTION_TYPE_PRAISE;
        $type       = Inbox::ENUM_OBJ_TYPE_PRAISE;

        try{
            \DB::beginTransaction();

            $result = app(PraiseService::class)->createPraise($ownerId, $objId, $objType, $collegeId);

            $this->inbox->send($fromId,$toId,$result->id,$content,$type,$actionType,$postAt);

            app(PraiseService::class)->incrementNumber($objType,$objId);

            \DB::commit();
        }catch (Exception $e){

            \DB::rollBack();
            throw new ApiException($e,60001);
        }

        return app(PraiseService::class)->formatSinglePraise($result);
    }

}