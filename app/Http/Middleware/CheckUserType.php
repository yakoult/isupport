<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Student;
use App\Faculty;
use App\Parents;

class CheckUserType
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
            switch (Auth::user()->type) {
                case '1':
                    $userinfo = Student::where('user_id', Auth::user()->id)->first();
                    break;
                case '2':
                    $userinfo = Faculty::where('user_id', Auth::user()->id)->first();
                    break;
                case '3':
                    $userinfo = Parents::where('user_id', Auth::user()->id)->first();
                    break;
                default:
                    $userinfo = null;
                    break;
            }
        }else{
            $userinfo = null;
        }
        $request->attributes->add(['userinfo' => $userinfo]);
        return $next($request);
    }
}
