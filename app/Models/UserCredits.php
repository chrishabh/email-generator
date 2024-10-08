<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class UserCredits extends Model
{
    use HasFactory,SoftDeletes;

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

    public static function initialFreeCredit($user_id)
    {
        UserCredits::insert([
            'order_id' => "Initial_Sign-up_credits",
            'user_id' => $user_id,
            'credits' => "100"
        ]);
    }

    public static function getUsedCredits()
    {
            $usedCreditsQuery = DB::table('user_credits')->join('users', 'users.id', '=','user_id')
                ->select(DB::raw('MAX(CAST(credits AS UNSIGNED)) - MIN(CAST(credits AS UNSIGNED)) AS used_credits'))->whereNull('users.deleted_at')
                ->groupBy('user_id', 'order_id');

            // Step 2: Use the subquery to calculate the total used credits
            $totalUsedCredits = DB::table(DB::raw("({$usedCreditsQuery->toSql()}) as sub"))
                ->mergeBindings($usedCreditsQuery) // Merge bindings of the inner query
                ->select(DB::raw('SUM(used_credits) as total_used_credits'))
                ->value('total_used_credits');



        return $totalUsedCredits;
    }

}
