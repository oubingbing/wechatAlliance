<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/5/27
 * Time: 13:19
 */

namespace App\Http\Service;


use App\Models\AdminApps;
use App\Models\Colleges;
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

    /**
     * 新建小程序
     *
     * @author yezi
     *
     * @param $appName
     * @param $appKey
     * @param $appSecret
     * @param $mobile
     * @param $college
     * @param $domain
     * @return mixed
     */
    public function create($appName,$appKey,$appSecret,$mobile,$college,$domain)
    {
        $result = WechatApp::create([
            WechatApp::FIELD_NAME => $appName,
            WechatApp::FIELD_APP_KEY => $appKey,
            WechatApp::FIELD_APP_SECRET => $appSecret,
            WechatApp::FIELD_ID_COLLEGE => $college,
            WechatApp::FIELD_MOBILE => $mobile,
            WechatApp::FIELD_ALLIANCE_KEY => str_random(16),
            WechatApp::FIELD_DOMAIN=>$domain
        ]);

        return $result;
    }

    /**
     * 管理用户和小程序
     *
     * @author yezi
     *
     * @param $app
     * @param $user
     * @return mixed
     */
    public function connectAdminWithApp($app,$user)
    {
        $adminApps = AdminApps::create([
            AdminApps::FIELD_ID_ADMIN=>$user->id,
            AdminApps::FIELD_ID_APP=>$app->id
        ]);

        return $adminApps;
    }

    /**
     * 根据用户ID获取小程序的注册信息
     *
     * @author yezi
     *
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAppByUserId($userId)
    {
        $result = WechatApp::query()
            ->with([WechatApp::REL_COLLEGE=>function($query){
                $query->select([Colleges::FIELD_ID,Colleges::FIELD_NAME]);
            }])
            ->whereHas(WechatApp::REL_ADMIN_APP,function ($query)use($userId){
            $query->where(AdminApps::FIELD_ID_ADMIN,$userId);
        })->get();

        return $result;
    }

}