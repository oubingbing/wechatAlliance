<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

}