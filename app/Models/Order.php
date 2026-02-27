<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    protected $dateFormat = 'Y-m-d';

    public static function createOrder($data = [])
    {
        return Order::insert($data);
    }

    public static function updateOrderStatus($order_id,$user_id,$credits,$plan_type)
    {
        $return = Order::whereNull('deleted_at')->where('order_id',$order_id)->where('user_id',$user_id)->update(['status'=>'Paid']);

        if($return){
            if(UserCredits::updateCredits($order_id,$user_id,$credits,$plan_type)){
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

    public static function getOrderDetails($user_id)
    {
        return Order::whereNull('deleted_at')->where('user_id',$user_id)->get();
    }

    public static function getInvoiceData($id)
    {
        return Order::join('users','orders.user_id', '=','users.id')->whereNull('users.deleted_at')->whereNull('orders.deleted_at')->where('orders.id',$id)->first();
    }

    public static function getOrderById($id)
    {
        return Order::whereNull('deleted_at')->where('order_id',$id)->first();
    }
}
