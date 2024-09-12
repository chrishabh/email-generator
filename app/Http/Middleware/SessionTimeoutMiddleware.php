<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){
            $lastActivity = session('lastActivityTime');
            $timeout       = config('session.lifetime')*60; //convert into second

            if($lastActivity && (time() - $lastActivity) > $timeout){ 
                
                // $response = redirect()->route('signin')->withCookie(cookie('error_message', 'Your session has expired due to inactivity.', 0));
                $cookie = cookie('error_message', 'Your session has expired due to inactivity.', 0);

                Auth::logout();
                // $request->session()->invalidate();
                // $request->session()->regenerateToken();
                session()->flush();
                return redirect()->route('signin')->withCookie($cookie);
                // return $response;
            }

            session(['lastActivityTime' =>time()]);
        }

        return $next($request);
    }
}
