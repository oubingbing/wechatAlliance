<?php

namespace App\Http\Wechat;


use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Service\ChatService;
use App\Http\Service\FriendService;
use App\Http\Service\PaginateService;
use App\Models\ChatMessage;
use Carbon\Carbon;
use League\Flysystem\Exception;

class ChatController extends Controller
{
    protected $chat;
    protected $friend;
    protected $paginateLogic;

    public function __construct(ChatService $chatLogic,FriendService $friendLogic,PaginateService $paginateLogic)
    {
        $this->chat = $chatLogic;
        $this->friend = $friendLogic;
        $this->paginateLogic = $paginateLogic;
    }

    /**
     * 发送消息
     *
     * @author yezi
     *
     * @param $friendId
     * @return mixed
     * @throws ApiException
     */
    public function sendMessage($friendId)
    {
        $user = request()->input('user');
        $content = request()->input('content');
        $attachments = request()->input('attachments');
        $type = ChatMessage::ENUM_STATUS_RED;
        $userId = $user->id;
        $postAt = Carbon::now();

        try{
            \DB::beginTransaction();

            $friend = $this->friend->checkFriendUnique($userId,$friendId);
            if(!$friend){
                $this->friend->createFriend($userId,$friendId);
                $this->friend->createFriend($friendId,$userId);
            }

            $result = $this->chat->sendMessage($userId,$friendId,$content,$attachments,$type,$postAt);
            $result = $this->chat->format($result);

            \DB::commit();
        }catch (Exception $exception){
            \DB::rollBack();
            throw new ApiException($exception);
        }

        return $result;
    }

    /**
     * 获取聊天列表
     *
     * @author yezi
     *
     * @param $friendId
     * @return array
     */
    public function chatList($friendId)
    {
        $user = request()->input('user');
        $pageSize = request()->input('page_size',10);
        $pageNumber = request()->input('page_number',1);
        $orderBy = request()->input('order_by','created_at');
        $sortBy = request()->input('sort_by','desc');

        $pageParams = ['page_size'=>$pageSize, 'page_number'=>$pageNumber];

        $query = $this->chat->builder($user->id,$friendId)->sort($orderBy,$sortBy)->done();

        $result = $this->paginateLogic->paginate($query,$pageParams, '*',function($item)use($user){
            return $this->chat->format($item);
        });

        $result['page_data'] = array_reverse(collect($result['page_data'])->toArray());

        $newMessages = collect($result)->filter(function ($item){

            if(empty($item->{ChatMessage::FIELD_READ_AT})){
                return true;
            }else{
                return false;
            }

        });

        $ids = collect(collect($newMessages['page_data'])->pluck(ChatMessage::FIELD_ID))->toArray();

        $this->chat->readMessage($ids);

        return $result;
    }

    /**
     * 获取新的消息
     *
     * @author yezi
     *
     * @param $friendId
     * @return array
     */
    public function getNewMessage($friendId)
    {
        $user = request()->input('user');

        $result = $this->chat->newMessage($user->id,$friendId);

        $data = array_reverse(collect($result)->toArray());

        return $data;
    }

    /**
     * 获取新的聊天信息
     *
     * @author yezi
     *
     * @return int
     */
    public function newLetter()
    {
        $user = request()->input('user');

        return $this->chat->myNewLetter($user->id);
    }

    /**
     * 好友列表
     *
     * @author yezi
     *
     * @return \Illuminate\Database\Eloquent\Collection|static|static[]
     */
    public function friends()
    {
        $user = request()->input('user');

        $friends = $this->friend->friends($user->id);

        $friends = collect($friends)->map(function ($friend){

            $friend = $this->friend->format($friend);

            return $friend;
        });

        return $friends;
    }

    /**
     * 删除聊天记录
     *
     * @author yezi
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id)
    {
        $user = request()->input('user');

        $result = $this->chat->delete($user->id,$id);

        return $result;
    }


}