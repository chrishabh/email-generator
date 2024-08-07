<?php


namespace App\Services\Auth;

use App\Models\User;
use App\Models\VerificationCode;
use App\Notifications\ConfirmationCode;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Validation\ValidationException;

class SignInService{

    static function signIn($credentials,$request){
         
        // Check if the email exists in the database
        $user = User::where('email', $credentials['email'])->first();
        if(!$user){
            return redirect()->back()->withErrors([
                'email' => '*The provided email does not exist in our records. Please register if you don\'t have an account.'
            ])->withInput();
            // throw ValidationException::withMessages([
            //     'email'=>['*The provided email does not exist in our records. Please register if you don\'t have an account.']
            // ]);
        }
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); 
            $otp = mt_rand(100000,999999);
            $verification_data = [
                'user_id' => Auth::User()->id,
                'email' =>  $credentials['email'],
                'verification_code' => $otp,
                'verification_type' => 'SIGN_IN',
            ];
            Notification::route('mail', $credentials['email'])->notify(new ConfirmationCode('Email Verification',['otp_code'=>$otp],'verification-code'));
            VerificationCode::addVerificationCode($verification_data);
            return  redirect()->intended('/verification');
        }else{
            return redirect()->back()->withErrors([
                'credentialsError' => 'The provided credentials do not match our records.'
            ])->withInput();
        }   
    }
}