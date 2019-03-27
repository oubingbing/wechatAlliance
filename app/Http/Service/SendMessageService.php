<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/22 0022
 * Time: 16:37
 */

namespace App\Http\Service;


use App\Exceptions\ApiException;
use App\Models\MessageSession;
use App\Models\SecretMessage;
use App\Models\SendMessage;
use Carbon\Carbon;

class SendMessageService
{
    /**
     * 保存发送记录
     *
     * @author yezi
     *
     * @param $mobile
     * @param $code
     * @param $status
     * @param $type
     * @param $sessionId
     * @param $expire
     * @return mixed
     */
    public function saveSendMessageLog($mobile,$code,$status,$type,$sessionId,$expire)
    {
        $result = SendMessage::create([
            SendMessage::FIELD_CODE               => $code,
            SendMessage::FIELD_MOBILE             => $mobile,
            SendMessage::FIELD_STATUS             => $status,
            SendMessage::FIELD_TYPE               =>$type,
            SendMessage::FIELD_ID_MESSAGE_SESSION => $sessionId,
            SendMessage::FIELD_EXPIRED_AT         => $expire
        ]);

        return $result;
    }

    public function getLogByCode($code)
    {
        $result = SendMessage::query()
            ->where(SendMessage::FIELD_CODE,$code)
            ->orderBy(SendMessage::FIELD_CREATED_AT,'DESC')
            ->first();
        return $result;
    }

    /**
     * 验证码校验
     *
     * @author yezi
     *
     * @param $code
     * @throws ApiException
     */
    public function validCode($code)
    {
        $log = $this->getLogByCode($code);
        if(!$log){
            throw new ApiException('验证码错误！',500);
        }

        if(Carbon::now()->gte(Carbon::parse($log->{SendMessage::FIELD_EXPIRED_AT}))){
            throw new ApiException('验证码已过期，请重新发送！',500);
        }
    }

    /**
     * 建立短息消息会话
     *
     * @author yezi
     *
     * @param $userId
     * @param $postPhone
     * @param $receivePhone
     * @param $objId
     * @param $objType
     * @return mixed
     */
    public function createMessageSession($userId,$postPhone,$receivePhone,$objId,$objType)
    {
        $result = MessageSession::create([
            MessageSession::FIELD_ID_USER       => $userId,
            MessageSession::FIELD_POST_PHONE    => $postPhone,
            MessageSession::FIELD_RECEIVE_PHONE => $receivePhone,
            MessageSession::FIELD_OBJ_ID        => $objId,
            MessageSession::FIELD_OBJ_TYPE      => $objType
        ]);

        return $result;
    }

    /**
     * 保存恋言信息
     *
     * @author yezi
     *
     * @param $postId
     * @param $receiveId
     * @param $sessionId
     * @param $content
     * @param $attachments
     * @param $code
     * @param $number
     * @param null $delayAt
     * @return mixed
     */
    public function saveSecretMessage($postId,$receiveId,$sessionId,$content,$attachments,$code,$number=0,$delayAt=null)
    {
        $result = SecretMessage::create([
            SecretMessage::FIELD_ID_MESSAGE_SESSION => $sessionId,
            SecretMessage::FIELD_ID_POST_USER       => $postId,
            SecretMessage::FIELD_ID_RECEIVE_USER    => $receiveId,
            SecretMessage::FIELD_CONTENT            => $content,
            SecretMessage::FIELD_ATTACHMENTS        => $attachments,
            SecretMessage::FIELD_CODE               => $code,
            SecretMessage::FIELD_NUMBER             => $number,
            SecretMessage::FIELD_DELAY_AT           => $delayAt
        ]);

        return $result;
    }

    /**
     * 统计当天表白墙发送的短信数量
     *
     * @author yezi
     *
     * @param $userId
     * @return int
     */
    public function countTodayPostSecretMessage($userId)
    {
        $number = SecretMessage::query()->where(SecretMessage::FIELD_ID_POST_USER,$userId)
            ->whereBetween(SecretMessage::FIELD_CREATED_AT,[Carbon::now()->startOfDay(),Carbon::now()->endOfDay()])
            ->count();

        return $number;
    }

}