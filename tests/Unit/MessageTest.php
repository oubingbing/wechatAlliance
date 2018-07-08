<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20 0020
 * Time: 14:42
 */

namespace Tests\Unit;


use App\Http\Service\NotificationService;
use App\Http\Service\TokenService;
use App\Http\Service\WeChatMessageService;
use App\Models\TemplateKeyWord;
use Tests\TestCase;

class MessageTest extends TestCase
{
    public function testToken()
    {
        $appId = 2;
        $result = app(TokenService::class)->accessToken($appId);

    }

    public function testSendMessage()
    {
        $appId = 2;
        $Message = new WeChatMessageService($appId);
        $result = $Message->getKeyWorld('AT1250');

        dd($result);
    }

    public function testAddKeyword()
    {
        $title = '内容被赞提醒';
        $key = 'AT1250';
        $ids = [9,7,8,10];
        $content = "主题、点赞人、点赞时间、累计获赞";

        TemplateKeyWord::create([
            TemplateKeyWord::FIELD_TITLE => $title,
            TemplateKeyWord::FIELD_KEY_WORD => $key,
            TemplateKeyWord::FIELD_KEY_WORD_IDS => $ids,
            TemplateKeyWord::FIELD_CONTENT => $content
        ]);
    }

    public function testTemplate()
    {
        $appId = 2;
        $Message = new WeChatMessageService($appId);

        $result = $Message->addTemplate('AT0280',[1,2,12]);

        dd($result);
    }

    public function testDeleteTemplate()
    {
        $appId = 2;
        $Message = new WeChatMessageService($appId);

        $result = $Message->deleteTemplate('BraiGxqtyD2OJCmtTIjhx6LyWO2XTKF_fvw_STGkM2k');
        dd($result);
    }

    public function testInitTemplate()
    {
        $appId = 5;
        $Message = new WeChatMessageService($appId);

        $Message->initTemplate();
    }

    public function testMessage()
    {
        $content = '【恋言网】您的消息编号：3306,信息：hi,有同学跟你表白了，登录微信小程序：小情书，在表白墙搜索你的手机号码即可查看！';
        $result = (new NotificationService(2))->sendMobileMessage(13425144866,$content);

        dd($result);
    }

}