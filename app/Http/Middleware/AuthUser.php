<?php

namespace App\Http\Middleware;

use App\Http\Service\AuthService;
use App\Models\AdminApps;
use App\Models\User;
use Closure;

class AuthUser
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

        $app = AdminApps::query()->where(AdminApps::FIELD_ID_ADMIN,$adminId)->first();
        if(!$app){
            //新建APP
            return redirect('admin/create_app');
        }

        $user = (new AuthService())->getAdminById($adminId);
        if(!$user){
            return redirect('/login');
        }

        $app =  $user->app();
        $request->offsetSet('user',$user);
        $request->offsetSet('app',$app);

        return $next($request);
    }
}
