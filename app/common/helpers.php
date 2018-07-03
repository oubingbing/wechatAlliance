<?php

use App\Jobs\SendInbox;
use App\Jobs\SendPhoneMessage;
use App\Jobs\SendTemplateMessage;
use Carbon\Carbon;

/**
 * 管理后台返回格式
 *
 * @author yezi
 */
if( ! function_exists('webResponse') ){
    function webResponse($msg='',$code=200,$data=null){
        $response = array();

        $response['code']=$code;
        $response['message'] = $msg;
        $response['data']=$data;
        $response['json_api'] = [
            'meta'=>[
                'name'=>'Json Api School',
                'copyright'=>Carbon::now()->year.' ouzhibing@outlook.com',
                'power_by'=>'yezi'
            ]
        ];
        return response($response);
    }
}

/**
 * 校验手机号码
 *
 * @author yezi
 */
if( ! function_exists('validMobile') ){
    function validMobile($mobile){
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile);
    }
}

/**
 * 发送微信模板消息
 *
 * @author yezi
 */
if( ! function_exists('senTemplateMessage') ){
    function senTemplateMessage($appId,$openId,$title,$values,$formId,$page='pages/index/index'){
        $jobData = [
            'open_id'=>$openId,
            'title'=>$title,
            'values'=>$values,
            'form_id'=>$formId,
            'page'=>$page
        ];
        $job = new SendTemplateMessage($appId,$jobData);
        dispatch($job)->onQueue('send_template_message');
    }
}

/**
 * 投递消息盒子
 *
 * @author yezi
 */
if( ! function_exists('senInbox') ){
    function senInbox($fromId, $toId, $objId, $content, $objType, $actionType, $postAt,$private=0){
        $job = new SendInbox($fromId, $toId, $objId, $content, $objType, $actionType, $postAt,$private);
        dispatch($job)->onQueue('send_inbox');
    }
}

/**
 * 发送短信消息
 *
 * @author yezi
 */
if( ! function_exists('sendMessage') ){
    function sendMessage($phone,$message){
        $job = new SendPhoneMessage($phone,$message);
        dispatch($job)->onQueue('send_message');
    }
}