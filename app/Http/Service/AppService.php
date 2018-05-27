<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/5/27
 * Time: 13:19
 */

namespace App\Http\Service;


use App\Models\WechatApp;

class AppService
{
    /**
     * 校验输入信息
     *
     * @author yeiz
     *
     * @param $request
     * @return array
     */
    public function valid($request)
    {
        $rules = [
            'app_name' => 'required',
            'app_key' => 'required',
            'app_secret' => 'required',
            'mobile' => 'required',
            'college' => 'required',
        ];
        $message = [
            'username.required' => '用户名不能为空！',
            'app_key.required' => 'APP_KEY不能为空！',
            'app_secret.required' => 'APP_SECRET不能为空！',
            'mobile.required' => '手机号不能为空！',
            'college.required' => '学校不能为空！',
        ];
        $validator = \Validator::make($request->all(),$rules,$message);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return ['valid'=>false,'message'=>$errors->first()];
        }else{
            return ['valid'=>true,'message'=>'success'];
        }
    }
    
    public function create($appName,$appKey,$appSecret,$mobile,$college)
    {
        $result = WechatApp::create([
            WechatApp::FIELD_NAME => $appName,
            WechatApp::FIELD_APP_KEY => $appKey,
            WechatApp::FIELD_APP_SECRET => $appSecret,
            WechatApp::FIELD_ID_COLLEGE => $college,
            WechatApp::FIELD_MOBILE => $mobile,
            WechatApp::FIELD_ALLIANCE_KEY => str_random(16)
        ]);

        return $result;
    }

}