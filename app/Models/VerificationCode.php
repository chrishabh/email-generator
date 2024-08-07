<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    public static function addVerificationCode($data = [])
    {
        VerificationCode::insert($data);
    }


    public static function getVerificationCode($user_id,$email)
    {
        return VerificationCode::whereNull('deleted_at')->where('user_id',$user_id)->where('email',$email)->where('verification_status','New')->where('verification_type','SIGN_IN')->first();
    }

    public static function expireVerificationStatus($user_id,$email)
    {
        return VerificationCode::whereNull('deleted_at')->where('user_id',$user_id)->where('email',$email)->where('verification_status','New')->where('verification_type','SIGN_IN')->update(['verification_status' => 'Expired','deleted_at' => Carbon::now()]);
    }

    public static function updateVerificationStatus($user_id,$email,$code)
    {
        return VerificationCode::whereNull('deleted_at')->where('user_id',$user_id)->where('email',$email)->where('verification_code',$code)->where('verification_status','New')->where('verification_type','SIGN_IN')->update(['verification_status' => 'Verified']);
    }
}
