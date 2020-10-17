<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

class Before
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
        $collegeId = $request->input("college_id");
        $request->offsetSet('college_id',$collegeId);

        return $next($request);
    }
}
