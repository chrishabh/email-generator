<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    use HasFactory;

    public static function createVerificationToken($data = [])
    {
        return UserVerification::insert($data);
    }

    public static function getVerificationDetails($token,$ip_address)
    {
        $return = UserVerification::whereNull('deleted_at')->where('url_token',$token)->exists();

        if($return){
            $time = UserVerification::select('created_at','user_id')->whereNull('deleted_at')->where('url_token',$token)->first();
            if(User::getUserdata($time->user_id)->email_verified == '1'){
                return response()->view('already-email-verified');
            }
            if(Carbon::parse($time->created_at)->addMinutes(env('EMAIL_VERIFICATION_TIME',60))->isPast()){
                UserVerification::whereNull('deleted_at')->where('url_token',$token)->update(['deleted_at'=>Carbon::now(),'verification_success_ip' => $ip_address]);
                return response()->view('email-verification-failed');
            }
            if(User::verifyUser($time->user_id)){
                UserVerification::whereNull('deleted_at')->where('url_token',$token)->update(['deleted_at'=>Carbon::now(),'verification_success_ip' => $ip_address]);
                return response()->view('email-verification-success');
            }

        }
        UserVerification::whereNull('deleted_at')->where('url_token',$token)->update(['deleted_at'=>Carbon::now(),'verification_success_ip' => $ip_address]);
        return response()->view('email-verification-failed');
    }
}
