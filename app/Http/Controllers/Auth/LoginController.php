<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordFormRequest;
use App\Http\Requests\Auth\SignInFormRequest;
use App\Models\User;
use App\Models\VerificationCode;
use App\Notifications\ConfirmationCode;
use App\Services\Auth\SignInService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.signin');
    }
    public function showResetForm()
    {
        return view('auth.reset-password');
    }

    public function login(Request $request)
    {
        try{
             
            $SigninFormRequest = new SignInFormRequest(); 
            $validator         = Validator::make($request->all(),$SigninFormRequest->rules());
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $credentials = [
                'email'    => $request->input('emailL'),
                'password' => $request->input('passwordL'),
            ];
            return SignInService::signIn($credentials,$request);
        }catch (ValidationException $e) {
            // Handle validation exceptions specifically
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Throwable $th) {
            // Handle other types of exceptions
            \Illuminate\Support\Facades\Log::error('Login failed: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Login failed: ' . $th->getMessage());
        }
         
    }

    public function resetPassword(Request $request)
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
                $uniquePassword = generateUniquePassword();
                if(User::updatePassword($user_data['email'],$uniquePassword,$ip)){
                    Notification::route('mail', $user_data['email'])->notify(new ConfirmationCode('Reset password',['password'=>$uniquePassword,'logo_url'=>url('assets/bouncee-logo.png'),'app_name'=>"bouncee",'support_link'=>"mailto::support@bouncee.net",'support_email'=>"support@bouncee.net",'year'=>"2024"],'reset-password-mail'));

                    return  redirect()->intended('/single');
                }
                
            }else{
                return redirect()->back()->withErrors("The email you entered is not registered. Please sign up first to proceed with the password reset request.")->withInput();
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



    // public static function verification(Request $request)
    // {
    //     $verification_code = $request->input('verification_code');

    //     $verification_data = VerificationCode::getVerificationCode(Auth::User()->id,Auth::User()->email);
    //     if(empty($verification_data)){
    //         return redirect()->back()->withErrors([
    //             'verification_code' => 'Invalid Verification Request.'
    //         ])->withInput();
    //     }

    //     if (Carbon::parse($verification_data->created_at)->addMinutes(env('TIME_OUT_FOR_VERIFYING_THE_EMAIL'))->isPast()) {
    //         VerificationCode::expireVerificationStatus(Auth::User()->id,Auth::User()->email);
    //         return redirect()->back()->withErrors([
    //             'verification_code' => 'The verification code you provided has expired.'
    //         ])->withInput();
    //     }

    //     if($verification_data->verification_code == $verification_code && $verification_data->email == Auth::User()->email)
    //     {
    //         VerificationCode::updateVerificationStatus(Auth::User()->id,Auth::User()->email,$verification_code);
    //         if(Auth::check()){
    //             $request->session()->regenerate(); 
    //             session(['lastActivityTime'=>time()]);
    //             return  redirect()->intended('/single');
    //         }
                
    //         else return  redirect()->intended('/');
            
    //     }else{
    //         return redirect()->back()->withErrors([
    //             'verification_code' => 'The verification code you entered is incorrect.'
    //         ])->withInput();
    //     }

    //     return redirect()->back()->withErrors([
    //         'verification_code' => 'Something went wrong.'
    //     ])->withInput();
    // }

    // public static function resendCode()
    // {
    //     if(VerificationCode::expireVerificationStatus(Auth::User()->id,Auth::User()->email)){
    //         $otp = mt_rand(100000,999999);
    //         $verification_data = [
    //             'user_id' => Auth::User()->id,
    //             'email' =>  Auth::User()->email,
    //             'verification_code' => $otp,
    //             'verification_type' => 'SIGN_IN',
    //             'created_at' => Carbon::now()
    //         ];
           
    //         Notification::route('mail', Auth::User()->email)->notify(new ConfirmationCode('Email Verification',['otp_code'=>$otp],'verification-code'));
    //         VerificationCode::addVerificationCode($verification_data);
    //         return  response()->json(['message' => "OTP Resent Successful!"]);
    //     }
    //     return  response()->json(['message' => "Something Went Wrong!"]);
    // }

}
