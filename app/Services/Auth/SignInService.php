<?php


namespace App\Services\Auth;

use App\Models\User;
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
            return  redirect()->intended('/');;
        }else{
            return redirect()->back()->withErrors([
                'credentialsError' => 'The provided credentials do not match our records.'
            ])->withInput();
        }   
    }
}