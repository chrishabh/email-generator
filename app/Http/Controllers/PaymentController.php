<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserCredits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Psy\Readline\Hoa\Console;
use Razorpay\Api\Api;
use SebastianBergmann\Environment\Console as EnvironmentConsole;

class PaymentController extends Controller
{
    protected $razorpay;


    public function __construct(Api $razorpay)
    {
        $this->razorpay = $razorpay;
    }

    public function createOrder(Request $request)
    {

        $verification_credits = [
            '9' => 5000,
            '14' => 10000,
            '28' => 25000,
            '45' => 50000,
            '75' => 100000,
            '125' => 200000,
            '250' => 500000,
            '450' => 1000000,
        ];

        $pack_amount = $request->input('input_value');
        $timestamp = Carbon::now()->timestamp;
        $receipt = "bouncee_".$timestamp;
        $currency = 'USD';
        
        $order_exists = Order::checkOrderExists(Auth::User()->id,$pack_amount);
        if(!empty($order_exists)){
            $order['id'] = $order_exists->order_id;
        }else{
            $order = $this->razorpay->order->create([
                'receipt' => $receipt,
                'amount' => $pack_amount*100, // amount in paise
                'currency' => $currency
            ]);
        }
        

        $bind_data = [
            'orderId' => $order['id'],
            'amount' => $pack_amount*100,
            'currency' => $currency,
            'company_name' => "Bouncee",
            'description' => '',
            'prefill_name' => Auth::User()->name,
            'prefill_email' => Auth::User()->email,
            'created_at' => Carbon::now(),
            'credits' => $verification_credits[$pack_amount],
        ];
        Order::createOrder([
            'receipt' => $receipt,
            'order_id' => $order['id'],
            'user_id' => Auth::User()->id,
            'amount' => $pack_amount,
            'currency' => $currency,
            'company_name' => "Bouncee",
            'prefill_name' => Auth::User()->name,
            'prefill_email' => Auth::User()->email,
        ]);
        return view('payment', $bind_data);
    }

    public function handlePayment(Request $request)
    {
        // handle payment success and failure
        // Validate payment signature
        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
        ];
        $order = $this->razorpay->order->fetch($request->razorpay_order_id);
        $bind_data = [
            'trnasaction_id' => $order->id??null,
            'amount_paied' => $order->amount_paid??null,
        ];

        $verification_credits = [
            '9' => 5000,
            '14' => 10000,
            '28' => 25000,
            '45' => 50000,
            '75' => 100000,
            '125' => 200000,
            '250' => 500000,
            '450' => 1000000,
        ];
        try {
            $this->razorpay->utility->verifyPaymentSignature($attributes);
      
            Order::updateOrderStatus($request->razorpay_order_id,Auth::User()->id,$verification_credits[($order->amount_paid)/100]);
             // Payment is successful
            return view('payment-success',$bind_data);
        } catch (\Exception $e) {
            // Payment failed
            return view('payment-failed',$bind_data);
        }
    }

    public static function getPricing()
    {
        $creditPoint =0;
        $headerData = array(); 
        if(Auth::check()){ 
            $data = UserCredits::getCreditPoint(Auth::user()->id); 
           
            if(!empty($data)){
                $creditPoint =$data->credits;
                
            }
        }
            
        $headerData['creditPoint'] = $creditPoint; 
        return view('verify.pricing')->with(compact('headerData'));
    }
}
