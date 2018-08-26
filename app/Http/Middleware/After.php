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
        $response = $next($request);

        return $response;
    }
}
