<?php

namespace App\Http\Middleware;

use App\Models\UserVerification;
use Closure;
use Illuminate\Http\Request;

class UserEmailVerification
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
        $ip_address     =   $request->ip();
        $path   =   $request->path();
        $token   =   str_replace('bouncee-verification/','',$path);
        if(!empty($token))
        {
            $cookie = cookie('email-verification', '', 60);

            return UserVerification::getVerificationDetails($token,$ip_address)->withCookie($cookie);;
        }else{
            return view('email-verification-failed');
        }
        return $next($request);
    }
}
