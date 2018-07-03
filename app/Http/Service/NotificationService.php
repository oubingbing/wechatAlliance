<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/25 0025
 * Time: 16:15
 */

namespace App\Http\Service;


class NotificationService
{
    private $appId;

    function __construct($appId)
    {
        $this->appId = $appId;
    }

    /**
     * 发送短信验证码
     *
     * @author yezi
     *
     * @param $phone
     * @return mixed
     */
    public function messageCode($phone)
    {
        $result = app(YunPianService::class)->sendMessageCode($phone);

        return $result;
    }

    /**
     * 发送微信模板消息
     *
     * @author yezi
     *
     * @param $openId
     * @param $templateId
     * @param $values
     * @param $fromId
     * @param string $page
     * @return mixed
     */
    public function templateMessage($openId,$title,$values,$fromId,$page='')
    {
        $result = (new WeChatMessageService($this->appId))->send($openId,$title,$values,$fromId,$page);

        return $result;
    }

    /**
     * 投递消息盒子
     *
     * @author yezi
     *
     * @param $fromId
     * @param $toId
     * @param $objId
     * @param $content
     * @param $objType
     * @param $actionType
     * @param $postAt
     * @param int $private
     * @return mixed
     */
    public function sendInbox($fromId, $toId, $objId, $content, $objType, $actionType, $postAt,$private=0)
    {
        $result = app(InboxService::class)->send($fromId, $toId, $objId, $content, $objType, $actionType, $postAt,$private);

        return $result;
    }

    /**
     * 发送短信消息
     *
     * @author yezi
     *
     * @param $mobile
     * @param $content
     * @return mixed
     */
    public function sendMobileMessage($mobile,$content)
    {
        $result = app(YunPianService::class)->sendMessage($content,$mobile);

        return $result;
    }

}