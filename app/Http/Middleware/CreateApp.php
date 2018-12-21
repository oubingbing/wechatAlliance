<?php

namespace App\Http\Middleware;

use App\Http\Service\AuthService;
use Closure;
use Illuminate\Support\Facades\Auth;

class CreateApp
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
        $adminId = AuthService::authUser();
        if(!$adminId){
            return redirect('/login');
        }

        $user = (new AuthService())->getAdminById($adminId);
        if(!$user){
            return redirect('/login');
        }

        $request->offsetSet('user',$user);

        return $next($request);
    }
}
