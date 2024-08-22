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

    public static function getCreditPoint($user_id){
        return self::where('user_id',$user_id)->whereNull('deleted_at')->orderBy('id', 'desc')->first();
    }


    public static function updateCreditsWhenEmailGetsVerify($user_id,$credits){
        $oldData = self::getCreditPoint($user_id);
        if($oldData){
            // deleted old order credits
            UserCredits::where('user_id',$user_id)->where('id',$oldData->id)->update(['deleted_at' => Carbon::now()]);
            // added new order credits with old one.
            return UserCredits::insert([
                'order_id' => $oldData->order_id,
                'user_id' => $user_id,
                'credits' => ($oldData->credits-$credits)
            ]);
        }
    }

}
