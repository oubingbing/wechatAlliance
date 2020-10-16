<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/19
 * Time: 下午10:07
 */

namespace Tests\Unit;


use App\Models\ChatMessage;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class ChatTest extends TestCase
{
    /**
     * @test
     */
    public function sendMessage()
    {
        /*$user = User::query()->first();

        $friendId = $user->id;
        $content = '你好';
        $attachments = '';
        $type = ChatMessage::ENUM_STATUS_RED;
        $userId = $user->id;
        $postAt = Carbon::now();

        $friend = app(FriendLogic::class)->checkFriendUnique($userId,$friendId);
        if(!$friend){
            app(FriendLogic::class)->createFriend($userId,$friendId);
            app(FriendLogic::class)->createFriend($friendId,$userId);
        }

        $result = 10 / 0;

        $result = app(ChatLogic::class)->sendMessage($userId,$friendId,$content,$attachments,$type,$postAt);

        return $result;*/
    }

}