<?php

namespace App\Http\Middleware;

use App\Models\ApiKeys;
use App\Models\UserCredits;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class VerifyApiKeyAndCredits
{
    
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->query('apiKey');
        $email  = $request->query('email');

        //check if email and apikey is exists
        if(!$apiKey || !$email){
            return response()->json([
                "result"=>'Invalid request.Both Email and api key is required.',
                "success"=>false,
                "code" =>400
            ],400);
        }

        // rate limit 
        $rateLimitKey = 'verify-api:' . $apiKey;
        if(RateLimiter::tooManyAttempts($rateLimitKey,5)){//allow 5 req in a min
            return response()->json([
                "success"=>false,
                "result" =>"Too many requests",
                "code" =>429
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
                "result"=>'Invalid API Key',
                "success"=>false,
                "code" =>401
            ],401);
        }

        $request->merge([
            'api_key_data'=>$apiKeysExist
        ]);
        $userId = $apiKeysExist->user_id;
        $userCredit= UserCredits::getCreditPoint($userId);
        $creditPoints = ($userCredit) ? $userCredit->credits : 0;
        if ($creditPoints < 1) {
            return response()->json([
                "success"=>"false",
                "result"=>"Insufficient verification credits",
                "code" =>402
            ],402);
        }
        return $next($request);
    }
}
