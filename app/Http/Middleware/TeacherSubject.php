<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Sched;
use App\Subjects;
use App\Faculty;
use App\Providers\RouteServiceProvider;

class TeacherSubject
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
        $schedid = $request->route('id');
        if (Auth::guard()->check()) {
            if (Auth::user()->type == 2 || Auth::user()->type == 0) {
                   $teacher = Faculty::where('user_id', Auth::user()->id)->first();
                   $sched = Sched::where('ID', $schedid)->with('subject')->with('section')->with('quiz')->with('files')->with('exam')->with('teacher')->first();
                   if ($teacher->ID == $sched->teacheruser_id) {
                       $request->attributes->add(['subjectinfo'=>$sched, 'userinfo'=>$teacher]);
                   }else{
                        return redirect(RouteServiceProvider::HOME);
                   }
            }else{
                return redirect(RouteServiceProvider::HOME);
            }
        }else{
            return redirect(RouteServiceProvider::HOME);
        }
        return $next($request);
    }
}
