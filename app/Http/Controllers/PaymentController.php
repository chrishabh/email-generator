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

    public function showCheckout(Request $request)
    {
        $currencySymbols = [
            'AFN' => '؋',
            'ALL' => 'L',
            'DZD' => 'د.ج',
            'USD' => '$',
            'EUR' => '€',
            'AOA' => 'Kz',
            'XCD' => '$',
            'ARS' => '$',
            'AMD' => '֏',
            'AWG' => 'ƒ',
            'AUD' => '$',
            'AZN' => '₼',
            'BSD' => '$',
            'BHD' => '.د.ب',
            'BDT' => '৳',
            'BBD' => '$',
            'BYN' => 'Br',
            'BZD' => '$',
            'XOF' => 'Fr',
            'BMD' => '$',
            'INR' => '₹',
            'BOB' => 'Bs.',
            'BAM' => 'KM',
            'BWP' => 'P',
            'NOK' => 'kr',
            'BRL' => 'R$',
            'BND' => '$',
            'BGN' => 'лв',
            'BIF' => 'FBu',
            'KHR' => '៛',
            'XAF' => 'Fr',
            'CAD' => '$',
            'CVE' => '$',
            'KYD' => '$',
            'CLP' => '$',
            'CNY' => '¥',
            'COP' => '$',
            'KMF' => 'CF',
            'CDF' => 'FC',
            'CRC' => '₡',
            'CUP' => '$',
            'CZK' => 'Kč',
            'DKK' => 'kr',
            'DJF' => 'Fdj',
            'DOP' => '$',
            'EGP' => '£',
            'SVC' => '$',
            'ETB' => 'Br',
            'FKP' => '£',
            'FJD' => '$',
            'GMD' => 'D',
            'GEL' => '₾',
            'GHS' => 'GH₵',
            'GIP' => '£',
            'GTQ' => 'Q',
            'GNF' => 'Fr',
            'GYD' => '$',
            'HTG' => 'G',
            'HNL' => 'L',
            'HKD' => '$',
            'HUF' => 'Ft',
            'ISK' => 'kr',
            'IDR' => 'Rp',
            'IRR' => '﷼',
            'IQD' => 'ع.د',
            'ILS' => '₪',
            'JMD' => '$',
            'JPY' => '¥',
            'JOD' => 'د.ا',
            'KZT' => '₸',
            'KES' => 'KSh',
            'KPW' => '₩',
            'KRW' => '₩',
            'KWD' => 'د.ك',
            'KGS' => 'лв',
            'LAK' => '₭',
            'LBP' => 'ل.ل',
            'LSL' => 'L',
            'LRD' => '$',
            'LYD' => 'ل.د',
            'CHF' => 'CHF',
            'MOP' => 'MOP$',
            'MGA' => 'Ar',
            'MWK' => 'MK',
            'MYR' => 'RM',
            'MVR' => 'Rf',
            'MRU' => 'UM',
            'MUR' => '₨',
            'MXN' => '$',
            'MDL' => 'L',
            'MNT' => '₮',
            'MAD' => 'MAD',
            'MZN' => 'MT',
            'MMK' => 'Ks',
            'NAD' => '$',
            'NPR' => '₨',
            'NZD' => '$',
            'NIO' => 'C$',
            'NGN' => '₦',
            'NOK' => 'kr',
            'OMR' => 'ر.ع.',
            'PKR' => '₨',
            'PAB' => 'B/.',
            'PGK' => 'K',
            'PYG' => '₲',
            'PEN' => 'S/',
            'PHP' => '₱',
            'PLN' => 'zł',
            'QAR' => '﷼',
            'RON' => 'lei',
            'RUB' => '₽',
            'RWF' => 'FRw',
            'SHP' => '£',
            'WST' => 'WS$',
            'STN' => 'Db',
            'SAR' => '﷼',
            'RSD' => 'дин.',
            'SCR' => '₨',
            'SLL' => 'Le',
            'SGD' => '$',
            'SBD' => '$',
            'SOS' => 'Sh',
            'ZAR' => 'R',
            'SSP' => '£',
            'LKR' => 'Rs',
            'SDG' => '£',
            'SRD' => '$',
            'SEK' => 'kr',
            'CHF' => 'CHF',
            'SYP' => '£',
            'TWD' => 'NT$',
            'TJS' => 'ЅМ',
            'TZS' => 'Sh',
            'THB' => '฿',
            'TOP' => 'T$',
            'TTD' => 'TT$',
            'TND' => 'د.ت',
            'TRY' => '₺',
            'TMT' => 'm',
            'UGX' => 'Sh',
            'UAH' => '₴',
            'AED' => 'د.إ',
            'GBP' => '£',
            'UYU' => '$U',
            'UZS' => "so'm",
            'VUV' => 'VT',
            'VES' => 'Bs.S',
            'VND' => '₫',
            'XPF' => '₣',
            'YER' => '﷼',
            'ZMW' => 'ZK',
            'ZWL' => 'Z$',
        ];
        
        $headerData = array(); 
        if(Auth::check()){ 
            $data = UserCredits::getCreditPoint(Auth::user()->id); 
           
            if(!empty($data)){
                $creditPoint =$data->credits;
                
            }
        }
            
        $headerData['creditPoint'] = $creditPoint??0; 
        $location_details = getCurrency($request->ip());
        $ip_currency = $location_details['currency'] ?? 'USD';
        $ip_country_code = $location_details['country_code'] ?? 'US';
        $converted_details = getConvertedAmount($request->price,$ip_currency);
        $pack_amount = $converted_details['amount'];
        $gst_amount = 0;//($pack_amount*env('gst_percentage'))/100;
        
        $pack_amount = $pack_amount+$gst_amount;
        $currency =  $converted_details['currency'];
        // You can pass plan details here when user clicks “Purchase”
        return view('verify.checkout', [
            'plan_name' => $request->plan_name,
            'price' => $request->price,
            'plan_currency' => $currencySymbols['USD'].' ',
            'credits' => $request->credits,
            'duration' => $request->duration,
            'base_price' => $converted_details['amount'],
            'gst' => env('gst_percentage')??0,
            'gst_amount' => $gst_amount??0,
            'Total_amount' => $pack_amount,
            'currency' => $currencySymbols[$ip_currency].' ',
        ])->with(compact('headerData'));
    }

    public function createOrder(Request $request)
    {
        //return view('notice');

        //return view('notice');

        $verification_credits = [
            '5'    => 3000,
            '10'   => 10000,
            '20'   => 25000,
            '50'   => 100000,
            '100'  => 250000,
            '200'  => 600000,
            '400'  => 1500000,
            '800'  => 5000000,
            '1500' => 10000000,
            '3000' => 25000000,
        ];

        $unlimited_plans = [
            '19'    => '7 Days',
            '49'   => '1 Month',
            '129'   => '3 Months',
            '249'   => '6 Months',
        ];

        $promo_code = ($request->promo_code == 'BOUNCEE10')?10:0;
        $pack_amount_us = $request->price;
        $credits = $request->credits;
        $plan = $request->plan_name;
        $timestamp = Carbon::now()->timestamp;
        $receipt = "bouncee_".$timestamp;
        $location_details = getCurrency($request->ip());
        $ip_currency = $location_details['currency'] ?? 'USD';
        $ip_country_code = $location_details['country_code'] ?? 'US';
        $converted_details = getConvertedAmount($request->price,$ip_currency);
        $pack_amount = $converted_details['amount'];
        $gst_amount = 0;//($pack_amount*env('gst_percentage'))/100;
        $pack_amount = $pack_amount - ($pack_amount*($promo_code/100));
        $pack_amount = round($pack_amount+$gst_amount);
        $currency =  $converted_details['currency'];
        $order_exists = Order::checkOrderExists(Auth::User()->id,$pack_amount);
        if(!empty($order_exists)){
            $order['id'] = $order_exists->order_id;
        }else{
            $order = $this->razorpay->order->create([
                'receipt' => $receipt,
                'amount' => $pack_amount*100,
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
            'credits' => ($plan == 'Limited')?$verification_credits[$pack_amount_us]:$unlimited_plans[$pack_amount_us],
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
            'plan_type' => $plan,
            'plan_amount' => $pack_amount_us,
            'plan_currency' => 'USD',
            'payment_ip' => $request->ip(),
            'country_code' => $ip_country_code,
            'country_currency' => $ip_currency,
            'promo_code' => $request->promo_code??null,
            'applied_gst_per' => env('gst_percentage')??null,
            'gst_amount' => $gst_amount??null,
            'gst_number' => $request->gst_number??null,
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

        $order_details = Order::getOrderById($request->razorpay_order_id);

        if(empty($order_details)){
            return view('payment-failed');
        }

        $bind_data = [
            'trnasaction_id' => $order->id??null,
            'amount_paied' => $order->amount_paid??null,
        ];

        $verification_credits = [
            '5'    => 3000,
            '10'   => 10000,
            '20'   => 25000,
            '50'   => 100000,
            '100'  => 250000,
            '200'  => 600000,
            '400'  => 1500000,
            '800'  => 5000000,
            '1500' => 10000000,
            '3000' => 25000000,
        ];

        $unlimited_plans = [
            '19'    => '7 Days',
            '49'   => '1 Month',
            '129'   => '3 Months',
            '249'   => '6 Months',
        ];
        // try {
            $this->razorpay->utility->verifyPaymentSignature($attributes);

            $credits =  $order_details['plan_type'] == 'Limited' ? $verification_credits[$order_details['plan_amount']] : $unlimited_plans[$order_details['plan_amount']];
      
            Order::updateOrderStatus($request->razorpay_order_id,Auth::User()->id, $credits, $order_details['plan_type']);
             // Payment is successful
            return view('payment-success',$bind_data);
        // } catch (\Exception $e) {
        //     // Payment failed
        //     return view('payment-failed',$bind_data);
        // }
    }

    public static function getPricing()
    {
        $headerData = array(); 
        if(Auth::check()){ 
            $data = UserCredits::getCreditPoint(Auth::user()->id); 
           
            if(!empty($data)){
                $creditPoint =$data->credits;
                
            }
        }
            
        $headerData['creditPoint'] = $creditPoint??0; 
        return view('verify.pricing')->with(compact('headerData'));
    }

    public static function getPaymentHistory()
    {
        $currencySymbols = [
            'AFN' => '؋',
            'ALL' => 'L',
            'DZD' => 'د.ج',
            'USD' => '$',
            'EUR' => '€',
            'AOA' => 'Kz',
            'XCD' => '$',
            'ARS' => '$',
            'AMD' => '֏',
            'AWG' => 'ƒ',
            'AUD' => '$',
            'AZN' => '₼',
            'BSD' => '$',
            'BHD' => '.د.ب',
            'BDT' => '৳',
            'BBD' => '$',
            'BYN' => 'Br',
            'BZD' => '$',
            'XOF' => 'Fr',
            'BMD' => '$',
            'INR' => '₹',
            'BOB' => 'Bs.',
            'BAM' => 'KM',
            'BWP' => 'P',
            'NOK' => 'kr',
            'BRL' => 'R$',
            'BND' => '$',
            'BGN' => 'лв',
            'BIF' => 'FBu',
            'KHR' => '៛',
            'XAF' => 'Fr',
            'CAD' => '$',
            'CVE' => '$',
            'KYD' => '$',
            'CLP' => '$',
            'CNY' => '¥',
            'COP' => '$',
            'KMF' => 'CF',
            'CDF' => 'FC',
            'CRC' => '₡',
            'CUP' => '$',
            'CZK' => 'Kč',
            'DKK' => 'kr',
            'DJF' => 'Fdj',
            'DOP' => '$',
            'EGP' => '£',
            'SVC' => '$',
            'ETB' => 'Br',
            'FKP' => '£',
            'FJD' => '$',
            'GMD' => 'D',
            'GEL' => '₾',
            'GHS' => 'GH₵',
            'GIP' => '£',
            'GTQ' => 'Q',
            'GNF' => 'Fr',
            'GYD' => '$',
            'HTG' => 'G',
            'HNL' => 'L',
            'HKD' => '$',
            'HUF' => 'Ft',
            'ISK' => 'kr',
            'IDR' => 'Rp',
            'IRR' => '﷼',
            'IQD' => 'ع.د',
            'ILS' => '₪',
            'JMD' => '$',
            'JPY' => '¥',
            'JOD' => 'د.ا',
            'KZT' => '₸',
            'KES' => 'KSh',
            'KPW' => '₩',
            'KRW' => '₩',
            'KWD' => 'د.ك',
            'KGS' => 'лв',
            'LAK' => '₭',
            'LBP' => 'ل.ل',
            'LSL' => 'L',
            'LRD' => '$',
            'LYD' => 'ل.د',
            'CHF' => 'CHF',
            'MOP' => 'MOP$',
            'MGA' => 'Ar',
            'MWK' => 'MK',
            'MYR' => 'RM',
            'MVR' => 'Rf',
            'MRU' => 'UM',
            'MUR' => '₨',
            'MXN' => '$',
            'MDL' => 'L',
            'MNT' => '₮',
            'MAD' => 'MAD',
            'MZN' => 'MT',
            'MMK' => 'Ks',
            'NAD' => '$',
            'NPR' => '₨',
            'NZD' => '$',
            'NIO' => 'C$',
            'NGN' => '₦',
            'NOK' => 'kr',
            'OMR' => 'ر.ع.',
            'PKR' => '₨',
            'PAB' => 'B/.',
            'PGK' => 'K',
            'PYG' => '₲',
            'PEN' => 'S/',
            'PHP' => '₱',
            'PLN' => 'zł',
            'QAR' => '﷼',
            'RON' => 'lei',
            'RUB' => '₽',
            'RWF' => 'FRw',
            'SHP' => '£',
            'WST' => 'WS$',
            'STN' => 'Db',
            'SAR' => '﷼',
            'RSD' => 'дин.',
            'SCR' => '₨',
            'SLL' => 'Le',
            'SGD' => '$',
            'SBD' => '$',
            'SOS' => 'Sh',
            'ZAR' => 'R',
            'SSP' => '£',
            'LKR' => 'Rs',
            'SDG' => '£',
            'SRD' => '$',
            'SEK' => 'kr',
            'CHF' => 'CHF',
            'SYP' => '£',
            'TWD' => 'NT$',
            'TJS' => 'ЅМ',
            'TZS' => 'Sh',
            'THB' => '฿',
            'TOP' => 'T$',
            'TTD' => 'TT$',
            'TND' => 'د.ت',
            'TRY' => '₺',
            'TMT' => 'm',
            'UGX' => 'Sh',
            'UAH' => '₴',
            'AED' => 'د.إ',
            'GBP' => '£',
            'UYU' => '$U',
            'UZS' => "so'm",
            'VUV' => 'VT',
            'VES' => 'Bs.S',
            'VND' => '₫',
            'XPF' => '₣',
            'YER' => '﷼',
            'ZMW' => 'ZK',
            'ZWL' => 'Z$',
        ];
        $payment_data = Order::getOrderDetails(Auth::User()->id);

        if(count($payment_data)>0){
            $payment_data =  $payment_data->toArray();
        }else{
            $payment_data = [];
        }

        $headerData = array(); 
        if(Auth::check()){ 
            $data = UserCredits::getCreditPoint(Auth::user()->id); 
           
            if(!empty($data)){
                $creditPoint =$data->credits;
                
            }
        }

        $credit_points = [
            '5'    => 3000,
            '9' => 5000,
            '14' => 10000,
            '28' => 25000,
            '45' => 50000,
            '75' => 100000,
            '125' => 200000,
            '250' => 500000,
            '450' => 1000000,
            '10'   => 10000,
            '20'   => 25000,
            '50'   => 100000,
            '100'  => 250000,
            '200'  => 600000,
            '400'  => 1500000,
            '800'  => 5000000,
            '1500' => 10000000,
            '3000' => 25000000,
            '19'   => '7 Days',
            '49'   => '1 Month',
            '129'   => '3 Months',
            '249'   => '6 Months',
        ];

        foreach($payment_data as &$value)
        {
            $value['credit_points'] = number_format($credit_points[$value['plan_amount']]);
            $value['amount'] = number_format($value['amount']);
            $value['amount_currency'] = $currencySymbols[$value['currency']].' ';
        }
            
        $headerData['creditPoint'] = $creditPoint??0; 
        $headerData['paymentData'] = $payment_data; 

        return view('verify.payment-history')->with(compact('headerData'));
    }

    public static function getInvoicePdf(Request $request)
    {
        $currencySymbols = [
            'AFN' => '؋',
            'ALL' => 'L',
            'DZD' => 'د.ج',
            'USD' => '$',
            'EUR' => '€',
            'AOA' => 'Kz',
            'XCD' => '$',
            'ARS' => '$',
            'AMD' => '֏',
            'AWG' => 'ƒ',
            'AUD' => '$',
            'AZN' => '₼',
            'BSD' => '$',
            'BHD' => '.د.ب',
            'BDT' => '৳',
            'BBD' => '$',
            'BYN' => 'Br',
            'BZD' => '$',
            'XOF' => 'Fr',
            'BMD' => '$',
            'INR' => '₹',
            'BOB' => 'Bs.',
            'BAM' => 'KM',
            'BWP' => 'P',
            'NOK' => 'kr',
            'BRL' => 'R$',
            'BND' => '$',
            'BGN' => 'лв',
            'BIF' => 'FBu',
            'KHR' => '៛',
            'XAF' => 'Fr',
            'CAD' => '$',
            'CVE' => '$',
            'KYD' => '$',
            'CLP' => '$',
            'CNY' => '¥',
            'COP' => '$',
            'KMF' => 'CF',
            'CDF' => 'FC',
            'CRC' => '₡',
            'CUP' => '$',
            'CZK' => 'Kč',
            'DKK' => 'kr',
            'DJF' => 'Fdj',
            'DOP' => '$',
            'EGP' => '£',
            'SVC' => '$',
            'ETB' => 'Br',
            'FKP' => '£',
            'FJD' => '$',
            'GMD' => 'D',
            'GEL' => '₾',
            'GHS' => 'GH₵',
            'GIP' => '£',
            'GTQ' => 'Q',
            'GNF' => 'Fr',
            'GYD' => '$',
            'HTG' => 'G',
            'HNL' => 'L',
            'HKD' => '$',
            'HUF' => 'Ft',
            'ISK' => 'kr',
            'IDR' => 'Rp',
            'IRR' => '﷼',
            'IQD' => 'ع.د',
            'ILS' => '₪',
            'JMD' => '$',
            'JPY' => '¥',
            'JOD' => 'د.ا',
            'KZT' => '₸',
            'KES' => 'KSh',
            'KPW' => '₩',
            'KRW' => '₩',
            'KWD' => 'د.ك',
            'KGS' => 'лв',
            'LAK' => '₭',
            'LBP' => 'ل.ل',
            'LSL' => 'L',
            'LRD' => '$',
            'LYD' => 'ل.د',
            'CHF' => 'CHF',
            'MOP' => 'MOP$',
            'MGA' => 'Ar',
            'MWK' => 'MK',
            'MYR' => 'RM',
            'MVR' => 'Rf',
            'MRU' => 'UM',
            'MUR' => '₨',
            'MXN' => '$',
            'MDL' => 'L',
            'MNT' => '₮',
            'MAD' => 'MAD',
            'MZN' => 'MT',
            'MMK' => 'Ks',
            'NAD' => '$',
            'NPR' => '₨',
            'NZD' => '$',
            'NIO' => 'C$',
            'NGN' => '₦',
            'NOK' => 'kr',
            'OMR' => 'ر.ع.',
            'PKR' => '₨',
            'PAB' => 'B/.',
            'PGK' => 'K',
            'PYG' => '₲',
            'PEN' => 'S/',
            'PHP' => '₱',
            'PLN' => 'zł',
            'QAR' => '﷼',
            'RON' => 'lei',
            'RUB' => '₽',
            'RWF' => 'FRw',
            'SHP' => '£',
            'WST' => 'WS$',
            'STN' => 'Db',
            'SAR' => '﷼',
            'RSD' => 'дин.',
            'SCR' => '₨',
            'SLL' => 'Le',
            'SGD' => '$',
            'SBD' => '$',
            'SOS' => 'Sh',
            'ZAR' => 'R',
            'SSP' => '£',
            'LKR' => 'Rs',
            'SDG' => '£',
            'SRD' => '$',
            'SEK' => 'kr',
            'CHF' => 'CHF',
            'SYP' => '£',
            'TWD' => 'NT$',
            'TJS' => 'ЅМ',
            'TZS' => 'Sh',
            'THB' => '฿',
            'TOP' => 'T$',
            'TTD' => 'TT$',
            'TND' => 'د.ت',
            'TRY' => '₺',
            'TMT' => 'm',
            'UGX' => 'Sh',
            'UAH' => '₴',
            'AED' => 'د.إ',
            'GBP' => '£',
            'UYU' => '$U',
            'UZS' => "so'm",
            'VUV' => 'VT',
            'VES' => 'Bs.S',
            'VND' => '₫',
            'XPF' => '₣',
            'YER' => '﷼',
            'ZMW' => 'ZK',
            'ZWL' => 'Z$',
        ];
        if(!empty($request->order_id)){

            $data = Order::getInvoiceData($request->order_id);

            $credit_points = [
                '5'    => 3000,
                '9' => 5000,
                '14' => 10000,
                '28' => 25000,
                '45' => 50000,
                '75' => 100000,
                '125' => 200000,
                '250' => 500000,
                '450' => 1000000,
                '10'   => 10000,
                '20'   => 25000,
                '50'   => 100000,
                '100'  => 250000,
                '200'  => 600000,
                '400'  => 1500000,
                '800'  => 5000000,
                '1500' => 10000000,
                '3000' => 25000000,
                '19'   => '7 Days',
                '49'   => '1 Month',
                '129'   => '3 Months',
                '249'   => '6 Months',
            ];

            $binded_data = [
                'order_number' => $data->order_id,
                'date' => Carbon::parse($data->order_created)->format('d M Y'),
                'logo_url' => url("/assets/logo.png"),
                'client' => $data->name,
                'company' => "bouncee",
                'amount_currency' => $currencySymbols[$data->currency].' ',
                'subtotal' => $data->amount - $data->gst_amount,
                'gst_amount' => $data->gst_amount,
                'gateway' => 'Razorpay',
                'transaction_id' => $data->order_id,
                'invoice_date' =>  Carbon::parse($data->order_created)->format('d M Y'),
                'gst' => env('gst_percentage')??0,
                'client_gst' => $data->gst_number??'N/A',
                'client_email' => Auth::User()->email,
                'items' => [[
                    'description' =>  number_format($credit_points[$data->plan_amount])." Verifications",
                    'amount' => $data->amount - $data->gst_amount,
                    
                ]],
                'total' => $data->amount
            ];
              // // Load a view and pass the data
            $pdf = Pdf::loadView('invoice-pdf', $binded_data)->setOption('encoding', 'UTF-8');

            // // Return the PDF as a download
            return $pdf->download('invoice-pdf');

        }
    }
}
