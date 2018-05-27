<?php

use Carbon\Carbon;

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

if( ! function_exists('validMobile') ){
    function validMobile($mobile){
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile);
    }
}