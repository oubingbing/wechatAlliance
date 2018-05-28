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

        $result = app(AppService::class)->getAppByUserId($user->id);

        return $result;
    }
}