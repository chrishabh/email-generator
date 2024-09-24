<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserCredits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pdf;
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
        //return view('notice');

        $verification_credits = [
            '5' => 2000,
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
            'company_name' => "bouncee",
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
            'company_name' => "bouncee",
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
            '5' => 2000,
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

    public static function getPaymentHistory()
    {
        $payment_data = Order::getOrderDetails(Auth::User()->id);

        if(count($payment_data)>0){
            $payment_data =  $payment_data->toArray();
        }else{
            $payment_data = [];
        }

        $creditPoint =0;
        $headerData = array(); 
        if(Auth::check()){ 
            $data = UserCredits::getCreditPoint(Auth::user()->id); 
           
            if(!empty($data)){
                $creditPoint =$data->credits;
                
            }
        }

        $credit_points = [
            '5' => 2000,
            '9' => 5000,
            '14' => 10000,
            '28' => 25000,
            '45' => 50000,
            '75' => 100000,
            '125' => 200000,
            '250' => 500000,
            '450' => 1000000,
        ];

        foreach($payment_data as &$value)
        {
            $value['credit_points'] = number_format($credit_points[$value['amount']]);
            $value['amount'] = number_format($value['amount']);
        }
            
        $headerData['creditPoint'] = $creditPoint; 
        $headerData['paymentData'] = $payment_data; 

        return view('verify.payment-history')->with(compact('headerData'));
    }

    public static function getInvoicePdf(Request $request)
    {
        if(!empty($request->order_id)){

            $data = Order::getInvoiceData($request->order_id);

            $credit_points = [
                '5' => 2000,
                '9' => 5000,
                '14' => 10000,
                '28' => 25000,
                '45' => 50000,
                '75' => 100000,
                '125' => 200000,
                '250' => 500000,
                '450' => 1000000,
            ]; 

            $binded_data = [
                'order_number' => $data->order_id,
                'date' => $data->created_at,
                'logo_url' => url("/assets/logo.png"),
                'client' => $data->name,
                'company' => "bouncee",
                'items' => [[
                    'description' =>  number_format($credit_points[$data->amount])." Verifications",
                    'amount' => $data->amount
                ]],
                'total' => $data->amount
            ];

            // Load a view and pass the data
            $pdf = Pdf::loadView('invoice-pdf', $binded_data)->setOption('enable-external-links', true);

            // Return the PDF as a download
            return $pdf->download('invoice-pdf');
        }
    }
}
