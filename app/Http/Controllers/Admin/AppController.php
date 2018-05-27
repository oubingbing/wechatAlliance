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
use Illuminate\Support\Facades\Request;

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

    public function store(Request $request)
    {
        $appName = $request->input('app_name');
        $appKey = $request->input('app_key');
        $appSecret = $request->input('app_secret');
        $mobile = $request->input('mobile');
        $college = $request->input('college');

        $appService = app(AppService::class);

        $valid = $appService->valid($request);
        if(!$valid){
            return webResponse($valid['message'],500);
        }

        $college = Colleges::query()->find($college);
        if(!$college){
            return webResponse('学校不存！',500);
        }

        $validMobile = validMobile($mobile);
        if(!$validMobile){
            return webResponse('手机号码格式错误',500);
        }

        $result = $appService->create($appName,$appKey,$appSecret,$mobile,$college);
        if($result){
            return webResponse('新建成功！',200);
        }else{
            return webResponse('新建失败！',500);
        }
    }
}