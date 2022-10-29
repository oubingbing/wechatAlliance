<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use App\Http\Service\AppService;
use App\Models\User;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class Wechat extends BaseMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws ApiException
     */
    public function handle($request, Closure $next)
    {
        if(!$request->isMethod('get')){
            try {
                if (! $user = JWTAuth::parseToken()->authenticate()) {
                    throw new ApiException('请登录后再操作',5000);
                }
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                throw new ApiException('登录已过期,请重新登录',5000);
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                throw new ApiException('登录已过期,请重新登录',5000);
            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                throw new ApiException('登录已过期,请重新登录',5000);
            }

            if(!$request->isMethod('get')){
                $black = $user->{User::REL_BLACK_LIST};
                if($black){
                    throw new ApiException("您已被列入黑名单，不可进行该操作",500);
                }
            }

            $request->offsetSet('user',$user);
        }else{
            if(JWTAuth::getToken()){
                try {
                    $user = JWTAuth::parseToken()->authenticate();
                } catch (\Exception $e) {
                    $user = new User();
                }
            }else{
                $user = new User();
            }

            $code = $request->input("app_code");
            $app = app(AppService::class)->getAppIdByCode($code);
            if(collect($app)->isNotEmpty()){
                $user->{User::FIELD_ID_APP} = $app->id;
            }

            $request->offsetSet('user',$user);
        }

        return $next($request);
    }
}
