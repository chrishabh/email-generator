<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInFormRequest;
use App\Models\User;
use App\Services\Auth\SignInService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.signin');
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
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.');
        }
         
    }
}
