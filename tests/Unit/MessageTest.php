<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/20 0020
 * Time: 14:42
 */

namespace Tests\Unit;


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
        $result = $Message->getKeyWorld('AT2039');

        dd($result);
    }

    public function testTemplate()
    {
        $appId = 2;
        $Message = new WeChatMessageService($appId);

        $result = $Message->addTemplate('AT0280',[1,4,3,6,8]);
    }

    public function testAddKeyword()
    {
        /*$title = '咨询回复通知';
        $key = 'AT2039';
        $ids = [1,5,4];
        $content = "";

        TemplateKeyWord::create([
            TemplateKeyWord::FIELD_TITLE => $title,
            TemplateKeyWord::FIELD_KEY_WORD => $key,
            TemplateKeyWord::FIELD_KEY_WORD_IDS => $ids,
            TemplateKeyWord::FIELD_CONTENT => $content
        ]);*/
    }

    public function testInitTemplate()
    {
        $appId = 2;
        $Message = new WeChatMessageService($appId);

        $result = $Message->initAppTemplate();

        self::assertTrue($result);
    }

}