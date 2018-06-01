<?php
/**
 * Created by PhpStorm.
 * User: bingbing
 * Date: 2018/5/27
 * Time: 10:59
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Service\AppService;
use App\Models\Admin;
use App\Models\Colleges;
use App\Models\WechatApp;
use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * 新建小程序视图
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createApp()
    {
        return view('admin.app.create');
    }

    /**
     * 新建小程序
     *
     * @author 叶子
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $user = $request->get('user');
        $appName = $request->input('app_name');
        $appKey = $request->input('app_key');
        $appSecret = $request->input('app_secret');
        $mobile = $request->input('mobile');
        $collegeId = $request->input('college_id');

        $appService = app(AppService::class);

        $valid = $appService->valid($request);
        if(!$valid){
            return webResponse($valid['message'],500);
        }

        $college = Colleges::query()->where(Colleges::FIELD_ID,$collegeId)->first();
        if(!$college){
            return webResponse('学校不存！',500);
        }

        $appCollege = WechatApp::query()->where(WechatApp::FIELD_ID_COLLEGE,$collegeId)->first();
        if($appCollege){
            return webResponse('您选择的学校已被注册！',500);
        }

        $validMobile = validMobile($mobile);
        if(!$validMobile){
            return webResponse('手机号码格式错误',500);
        }

        try {
            \DB::beginTransaction();

            $domain = env('WECHAT_DOMAIN');
            $result = $appService->create($appName,$appKey,$appSecret,$mobile,$collegeId,$domain);
            if($result){
                $appService->connectAdminWithApp($result,$user);

                \DB::commit();
                return webResponse('新建成功！',200,'/admin');
            }else{
                return webResponse('新建失败！',500);
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return webResponse($e,500);
        }
    }

    /**
     * 小程序的信息
     *
     * @author yezi
     *
     * @return mixed
     */
    public function appInfo()
    {
        $user = request()->get('user');

        $app = $user->app();
        $app->status_string = collect([
            WechatApp::ENUM_STATUS_TO_BE_AUDIT=>"审核中",
            WechatApp::ENUM_STATUS_ON_LINE=>"运行中",
            WechatApp::ENUM_STATUS_WE_CHAT_AUDIT=>"微信审核中",
            WechatApp::ENUM_STATUS_CLOSED=>"已下线",
        ])->get((integer)$app->{WechatApp::FIELD_STATUS});

        $app->college = Colleges::query()->where(Colleges::FIELD_ID,$app->{WechatApp::FIELD_ID_COLLEGE})->value(Colleges::FIELD_NAME);
        if($app->{WechatApp::FIELD_STATUS} === WechatApp::ENUM_STATUS_TO_BE_AUDIT){
            $app->{WechatApp::FIELD_ALLIANCE_KEY} = '';
            $app->{WechatApp::FIELD_DOMAIN} = '';
        }

        return webResponse('ok',200,$app);
    }

    /**
     * 切换到微信审核模式
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function weChatAudit()
    {
        $user = request()->input('user');
        $app = $user->app();
        if(!$app){
            return webResponse('应用不存在！',500);
        }

        if($app->{WechatApp::FIELD_STATUS} != WechatApp::ENUM_STATUS_ON_LINE){
            return webResponse('小程序当前状态不是正常模式，无法切换到审核模式',500);
        }
        try{
            \DB::beginTransaction();

            $appService = app(AppService::class);

            $checkResult = $appService->canSwitchModel($app);
            if(!$checkResult['status']){
                return webResponse($checkResult['message'],500);
            }

            $result = $appService->WeChatAuditModel($app->{WechatApp::FIELD_ID});
            if($result->{WechatApp::FIELD_STATUS} === WechatApp::ENUM_STATUS_WE_CHAT_AUDIT){
                \DB::commit();
                return webResponse('开启微信审核模式成功！',200);
            }else{
                \DB::commit();
                return webResponse('开启微信审核模式失败！',500);
            }

        }catch (Exception $e){
            \DB::rollBack();
            return webResponse($e,500);
        }
    }

    /**
     * 恢复正常状态
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function online()
    {
        $user = request()->input('user');
        $app = $user->app();
        if(!$app){
            return webResponse('应用不存在！',500);
        }

        if($app->{WechatApp::FIELD_STATUS} != WechatApp::ENUM_STATUS_WE_CHAT_AUDIT){
            return webResponse('小程序当前状态不是审核模式，无法切换到正常模式',500);
        }

        $appService = app(AppService::class);

        try{
            \DB::beginTransaction();

            $checkResult = $appService->canSwitchModel($app);
            if(!$checkResult['status']){
                return webResponse($checkResult['message'],500);
            }

            $result = $appService->onlineModel($app->{WechatApp::FIELD_ID});
            if($result->{WechatApp::FIELD_STATUS} === WechatApp::ENUM_STATUS_ON_LINE){
                \DB::commit();
                return webResponse('恢复正常状态成功！',200);
            }else{
                \DB::commit();
                return webResponse('恢复成长状态失败！',500);
            }

        }catch (Exception $e){
            \DB::rollBack();
            return webResponse($e,500);
        }
    }

    /*
     * 部署教程
     *
     * @author yezi
     */
    public function deployStep()
    {
        return view('admin.app.deploy');
    }
}