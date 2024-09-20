<?php


namespace App\Services\Auth;

use App\Models\User;
use App\Models\UserCredits;
use App\Models\UserVerification;
use App\Notifications\ConfirmationCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class RegisterService{

    static function signup($request){
        $user = User::insertGetId([
            'name'                    => $request->name,
            'email'                    => $request->email,
            'password'                 => Hash::make($request->password),
            'role'                     => $request->role??'user',
            'no_of_email_verification' => $request->no_of_email_verification,
        ]); 

        $bytes = random_bytes(16);
        $timestamp = Carbon::now()->timestamp;
        $str = '?userid=' . $user . '?email=' . $request->email .'?timestamp'.$timestamp. '?uniquecode=' . bin2hex($bytes);
        $token = (base64_encode($str));
        $verification_link = [
          'user_id' => $user,
          'url_token' => $token,
          'verification_request_ip' => $request->ip(),
          'created_at' =>  Carbon::now(),
        ];
        $link = url("/bouncee-verification")."/".$token;
        if(UserVerification::createVerificationToken($verification_link)){
            Notification::route('mail', $request->email)->notify(new ConfirmationCode('Bouncee Verification',['User'=>$request->name,'verification_link'=>$link],'verification-template'));
            UserCredits::initialFreeCredit(User::getUserId($request->email));
            return redirect()->route('signin')->with('success', "Registration successful! Please check your inbox, junk, or spam folder for a verification email. Once you verify your email, you'll be able to log in.");

        }
        
        return redirect()->route('signin')->with('success', 'Something went wrong! Please try again.');
    }
}