<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Service\QiNiuService;
use App\Models\Colleges;

class IndexController extends Controller
{
    public function dashboard()
    {
        $user = request()->get('user');

        return view('admin.dashboard',['user'=>$user]);
    }

    /**
     * 进入管理后台首页
     *
     * @author yezi
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = request()->get('user');
        $app = $user->app();

        return view('admin.index',['user'=>$user,'app'=>$app]);
    }

    /**
     * 获取七牛上传凭证
     *
     * @author yezi
     *
     * @return mixed
     */
    public function getUploadToken()
    {
        $token = app(QiNiuService::class)->getToken();

        return webResponse('ok',200,$token);
    }

    public function colleges()
    {
        $colleges = Colleges::query()->get([Colleges::FIELD_ID,Colleges::FIELD_NAME]);

        return webResponse('ok',200,$colleges);
    }

}