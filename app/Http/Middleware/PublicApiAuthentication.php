<?php

namespace App\Http\Middleware;

use App\Models\ApiKeys;
use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class PublicApiAuthentication
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
        $apiKey = $request->query('apiKey');

         //check if email and apikey is exists
         if(!$apiKey){
            return response()->json([
                "result"=>'Invalid request.Api key is required.',
                "success"=>false,
                "code" =>400
            ],400);
        }

        $path = $request->path();
        $path = str_replace('api/','',$path);
        $rateLimitKey = $path . $apiKey;
        if(RateLimiter::tooManyAttempts($rateLimitKey,120)){//allow 5 req in a min
            return response()->json([
                "success"=>false,
                "code" =>429,
                "result" =>"Too many requests"
            ],429);
        }
        RateLimiter::hit($rateLimitKey,60);

        // validate api key

        $apiKeysExist = ApiKeys::where([
            ['key','=',$apiKey],
            ['is_deleted','=','0'],
            ['status','=','Enabled'],
            ['deleted_at','=',null],
        ])->whereIn('action', ['created', 'regenerated']) ->first();
 
        if(!$apiKeysExist){
            return response()->json([
                "success"=>false,
                "code" =>401,
                "result"=>'Invalid API Key',
            ],401);
        }

        $request->merge([
            'api_key_data'=>$apiKeysExist
        ]);
       
        return $next($request);
    }
}
