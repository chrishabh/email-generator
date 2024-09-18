<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory;

    public static function createOrder($data = [])
    {
        return Order::insert($data);
    }

    public static function updateOrderStatus($order_id,$user_id,$credits)
    {
        $return = Order::whereNull('deleted_at')->where('order_id',$order_id)->where('user_id',$user_id)->update(['status'=>'Paid']);

        if($return){
            if(UserCredits::updateCredits($order_id,$user_id,$credits)){
                notifyCreditBalance($credits,Auth::User()->name);
                return 1;
            }
        }
        return false;
    }

    public static function checkOrderExists($user_id,$amount)
    {
        return Order::whereNull('deleted_at')->where('user_id',$user_id)->where('amount',$amount)->where('status','Created')->first();
    }
}
