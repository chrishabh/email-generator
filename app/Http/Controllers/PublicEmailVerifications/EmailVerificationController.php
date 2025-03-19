<?php

namespace App\Http\Controllers\PublicEmailVerifications;
 
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\User;
use App\Models\UserCredits;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $email = $request->query('email');
        $emailController = new EmailController();
        $userId = $request->api_key_data->user_id;
        $user = User::find($userId);

        // Call the isValidEmail function from EmailController
        $status = $emailController->isValidEmail($email,true, $userId,$userId);  
        UserCredits::updateCreditsWhenEmailGetsVerify($userId ,1);
        return response()->json([
            "status"=>$status,
            "success"=>true,
            "code" =>200,
            "email"=> $email,
            "domain" => explode('@', $email)[1],
            "user" =>$user->name 
        ],200);
    }
}
