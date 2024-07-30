<?php


namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterService{

    static function signup($request){
        $user = User::create([
            'name'                    => $request->name,
            'email'                    => $request->email,
            'password'                 => Hash::make($request->password),
            'role'                     => $request->role??'user',
            'no_of_email_verification' => $request->no_of_email_verification,
        ]); 
        if($user){
            return redirect()->route('signin')->with('success', 'Registration successful! Please login.');
        }
    }
}