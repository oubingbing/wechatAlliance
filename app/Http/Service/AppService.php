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
use App\Models\WeChatTemplate;

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

    /**
     * 开启微信审核模式
     *
     * @author yezi
     *
     * @param $appId
     * @return int
     */
    public function WeChatAuditModel($appId)
    {
        $result = $this->updateStatus($appId,WechatApp::ENUM_STATUS_WE_CHAT_AUDIT);

        return $result;
    }

    /**
     * 上线模式
     *
     * @author yezi
     *
     * @param $appId
     * @return int
     */
    public function onlineModel($appId)
    {
        $result = $this->updateStatus($appId,WechatApp::ENUM_STATUS_ON_LINE);

        return $result;
    }

    /**
     * 关闭应用
     *
     * @author yezi
     *
     * @param $appId
     * @return int
     */
    public function closeModel($appId)
    {
        $result = $this->updateStatus($appId,WechatApp::ENUM_STATUS_CLOSED);

        return $result;
    }

    /**
     * 根据应用ID更新状态
     *
     * @author yezi
     *
     * @param $appId
     * @param $status
     * @return int
     */
    public function updateStatus($appId,$status)
    {
        $result = WechatApp::query()->find($appId);
        $result->{WechatApp::FIELD_STATUS} = $status;
        $result->save();

        return $result;
    }

    /**
     * 用户是否可以切换应用模式
     *
     * @author yezi
     *
     * @param $app
     * @return array
     */
    public function canSwitchModel($app)
    {
        $status = $app->{WechatApp::FIELD_STATUS};
        if($status === WechatApp::ENUM_STATUS_TO_BE_AUDIT || $status === WechatApp::ENUM_STATUS_CLOSED){
            $errorString = ($status === WechatApp::ENUM_STATUS_TO_BE_AUDIT?'应用未审核通过，不允许切换模式！':'应用处于下线状态，不允许切换模式！');
            return ['status'=>false,'message'=>$errorString];
        }else{
            return ['status'=>true,'message'=>'ok'];
        }
    }

    public function getTemplateByAppId($appId)
    {
        $templates = WeChatTemplate::query()
            ->where(WeChatTemplate::FIELD_ID_APP,$appId)
            ->select(WeChatTemplate::FIELD_ID_TEMPLATE,WeChatTemplate::FIELD_TITLE)
            ->get();

        return $templates;
    }

}