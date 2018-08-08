<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\WechatApp;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class After
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->input('user');
        $response = $next($request);
        $config = WechatApp::query()->where(WechatApp::FIELD_ID,$user[User::FIELD_ID_APP])->value(WechatApp::FIELD_STATUS);
        if($config == WechatApp::ENUM_STATUS_WE_CHAT_AUDIT){
            dd('hello world！');
        }else{
            return $response;
        }
    }
}
