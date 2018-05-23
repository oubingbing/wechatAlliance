<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/15
 * Time: 下午12:01
 */

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\InboxService;
use Carbon\Carbon;
use Exception;

class InboxController extends Controller
{
    protected $inboxLogic;

    public function __construct(InboxService $inboxRepository)
    {
        $this->inboxLogic = $inboxRepository;
    }

    /**
     * 往消息盒子投递信息
     *
     * @author yezi
     *
     * @return mixed
     */
    public function sendInbox()
    {
        $user = request()->input('user');
        $fromId = $user->id;
        $toId = request()->input('to_id');
        $objId = request()->input('obj_id');
        $objType = request()->input('obj_type');
        $actionType = request()->input('action_type');
        $content = request()->input('content');
        $postAt = Carbon::now();

        $result = $this->inboxLogic->send($fromId, $toId, $objId, $content, $objType, $actionType, $postAt);

        return $result;
    }

    /**
     * 检测是否有新的消息
     *
     * @author yezi
     *
     * @param $type
     * @return int
     */
    public function getNewInbox($type)
    {
        $user = request()->input('user');

        return $this->inboxLogic->getNewInboxByType($user->id, $type);
    }

    /**
     * 获取用户的消息列表
     *
     * @author yezi
     *
     * @param $type
     * @param $messageType
     * @return mixed|static
     * @throws ApiException
     */
    public function userInbox($type, $messageType)
    {
        $user = request()->input('user');
        $pageSize = request()->input('page_size',10);
        $pageNumber = request()->input('page_number',1);

        $pageParams = ['page_size'=>$pageSize, 'page_number'=>$pageNumber];

        try {
            \DB::beginTransaction();

            $inboxList = $this->inboxLogic->getInboxList($user->id, $type, $messageType,$pageParams);

            $this->inboxLogic->readInbox($user->id);

            $inboxList['page_data'] = $this->inboxLogic->formatInboxList($inboxList['page_data']);

            \DB::commit();
        } catch (Exception $e) {

            \DB::rollBack();
            throw new ApiException($e, 60001);
        }

        return $inboxList;
    }

}