<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/22 0022
 * Time: 16:37
 */

namespace App\Http\Service;


use App\Exceptions\ApiException;
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
            SendMessage::FIELD_CODE=>$code,
            SendMessage::FIELD_MOBILE=>$mobile,
            SendMessage::FIELD_STATUS=>$status,
            SendMessage::FIELD_TYPE=>$type,
            SendMessage::FIELD_ID_MESSAGE_SESSION=>$sessionId,
            SendMessage::FIELD_EXPIRED_AT=>$expire
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

}