<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/22 0022
 * Time: 16:37
 */

namespace App\Http\Service;


use App\Models\SendMessage;

class SendMessageService
{
    public function saveSendMessageLog($mobile,$code,$name='')
    {
        SendMessage::create([

        ]);
    }

}