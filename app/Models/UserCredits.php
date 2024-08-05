<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCredits extends Model
{
    use HasFactory;

    public static function updateCredits($order_id,$user_id,$credits){

        if(UserCredits::whereNull('deleted_at')->where('user_id',$user_id)->exists())
        {
            // Fetch existing credits
            $old_credits = UserCredits::whereNull('deleted_at')->where('user_id',$user_id)->first()->credits;

            // deleted old order credits
            UserCredits::whereNull('deleted_at')->where('user_id',$user_id)->update(['deleted_at' => Carbon::now()]);

            // added new order credits with old one.
            return UserCredits::insert([
                'order_id' => $order_id,
                'user_id' => $user_id,
                'credits' => ($credits+$old_credits)
            ]);

        }else{
            // added new order credits.
            return UserCredits::insert([
                'order_id' => $order_id,
                'user_id' => $user_id,
                'credits' => $credits
            ]);
        }
    }
}