<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterFormRequest;
use App\Models\Lookup;
use App\Models\User;
use App\Services\Auth\RegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $dropDownData = Lookup::getDataByLookupType('HMEV');
        return view('auth.signup')->with(compact('dropDownData'));
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
}
