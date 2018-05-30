<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\AdminApps;
use Closure;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::guard('admin')->user();

        $app = AdminApps::query()->where(AdminApps::FIELD_ID_ADMIN,$user->id)->first();
        if(!$app){
            //新建APP
            return redirect('admin/create_app');
        }

        $request->offsetSet('user',$user);

        return $next($request);
    }
}
