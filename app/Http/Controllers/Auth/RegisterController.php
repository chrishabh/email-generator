<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterFormRequest;
use App\Http\Requests\Auth\ResetPasswordFormRequest;
use App\Models\Lookup;
use App\Models\User;
use App\Models\UserVerification;
use App\Notifications\ConfirmationCode;
use App\Services\Auth\RegisterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $dropDownData = Lookup::getDataByLookupType('HMEV');
        return view('auth.signup')->with(compact('dropDownData'));
    }

    public function showVerificatinForm()
    {
        return view('auth.email-verification');
    }

    public function signup(Request $request)
    {
        try {
            $registerFormRequest = new RegisterFormRequest(); 
            $validator           = Validator::make($request->all(),$registerFormRequest->rules()); 
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            return RegisterService::signup($request);

        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error('Registration failed: ' . $th->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
        }
 
    }

    public static function sendVerificationEmail(Request $request)
    {
        try{
             
            $FormRequest = new ResetPasswordFormRequest();
            $validator         = Validator::make($request->all(),$FormRequest->rules());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $user_data = User::getUserdata($request->input('emailL'));
            $ip = $request->ip();
            if(!empty($user_data)){
                if($user_data->email_verified == '1'){
                    return redirect()->back()->withErrors("Email is already Verified!");
                }
                $bytes = random_bytes(16);
                $timestamp = Carbon::now()->timestamp;
                $str = '?userid=' . $user_data->id . '?email=' .$user_data->email .'?timestamp'.$timestamp. '?uniquecode=' . bin2hex($bytes);
                $token = (base64_encode($str));
                $verification_link = [
                'user_id' => $user_data->id,
                'url_token' => $token,
                'verification_request_ip' => $ip,
                'created_at' =>  Carbon::now(),
                ];
                $link = url("/bouncee-verification")."/".$token;
                UserVerification::whereNull('deleted_at')->where('user_id',$user_data->id)->update(['deleted_at'=>Carbon::now()]);
                if(UserVerification::createVerificationToken($verification_link)){
                    Notification::route('mail', $user_data->email)->notify(new ConfirmationCode('Bouncee Verification',['User'=>$user_data->name,'verification_link'=>$link],'verification-template'));
                   
                    return redirect()->route('signin')->with('success', "Please check your inbox, junk, or spam folder for a verification email. Once you verify your email, you'll be able to log in.");
                }        
            }else{
                return redirect()->back()->withErrors("The email you entered is not registered. Please sign up first to proceed with the email verification proccess.")->withInput();
            }
        }catch (ValidationException $e) {
            // Handle validation exceptions specifically
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Throwable $th) {
            // Handle other types of exceptions
            \Illuminate\Support\Facades\Log::error('Reset Request Failed: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Reset Request Failed: ' . $th->getMessage());
        }
    }
}
