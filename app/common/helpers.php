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