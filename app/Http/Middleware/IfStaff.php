<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Closure;

class IfStaff
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
        if (Auth::guard()->check()) {
            if (Auth::user()->type != 2) {
                return abort(403);
            }else{
                return $next($request);
            }
        }else{
            return abort(403);
        }
    }
}
