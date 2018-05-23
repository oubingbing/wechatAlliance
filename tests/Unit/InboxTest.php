<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/16
 * Time: 下午4:27
 */

namespace Tests\Unit;


use App\Http\Logic\InboxLogic;
use App\Inbox;
use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InboxTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 测试发送消息盒子
     *
     * @author yezi
     *
     * @test
     */
    public function send()
    {
        $user = User::query()->first();
        $post = Post::query()->first();

        $fromId = $user->id;
        $toId = $user->id;
        $objId = $post->id;
        $content = '你的帖子被评论了';
        $objType = Inbox::ENUM_OBJ_TYPE_POST;
        $actionType = Inbox::ENUM_ACTION_TYPE_COMMENT;
        $postAt = Carbon::now();

        $result = app(InboxLogic::class)->send($fromId,$toId,$objId,$content,$objType,$actionType,$postAt);

        self::assertNotEmpty($result);
        self::assertEquals($fromId,$result->{Inbox::FIELD_ID_FROM});
    }

    /**
     * 获取新的消息盒子数
     *
     * @author yezi
     *
     * @test
     */
    public function countNewInbox()
    {
        $this->beginDatabaseTransaction();

        $user = User::query()->first();
        $type = Inbox::ENUM_OBJ_TYPE_POST;

        $result = app(InboxLogic::class)->getNewInboxByType($user->id,$type);

        self::assertNotEmpty($result);
    }

}