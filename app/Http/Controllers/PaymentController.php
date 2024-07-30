<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    protected $razorpay;

    public function __construct(Api $razorpay)
    {
        $this->razorpay = $razorpay;
    }

    public function createOrder()
    {
        $order = $this->razorpay->order->create([
            'receipt' => 'order_rcptid_12',
            'amount' => 10000, // amount in paise
            'currency' => 'INR'
        ]);

        return  $order['id'];
        return view('payment', ['orderId' => $order['id']]);
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

        try {
            $this->razorpay->utility->verifyPaymentSignature($attributes);
            // Payment is successful
            return 'Payment successful';
        } catch (\Exception $e) {
            // Payment failed
            return 'Payment failed';
        }
    }
}
