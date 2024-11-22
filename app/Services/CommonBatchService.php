<?php


namespace App\Services;

use App\Models\User;
use App\Notifications\ConfirmationCode;
use Illuminate\Support\Facades\Notification;

class CommonBatchService{

    public static function sendVerificationActionEmail()
    {
        if(env('VERIFICATION_ACTION')){
            $user_data = User::getUnVerifiedUsers();
            $count = 0;
            foreach($user_data as $value)
            {
                Notification::route('mail', $value['email'])->notify(new ConfirmationCode('Action Required: Complete Your Email Verification on bouncee.net',[],'verification-action-required'));
                echo "Email Triggered to: " .$value['email']; 
                echo "\n"; 
                $count++;
            }
            echo "Total Email Sent:" .$count; 
            echo "\n"; 
        }else{
            $email = env('TEST_EMAIL');
            echo "Test Email Triggered:" .$email; 
            echo "\n"; 
            Notification::route('mail', $email)->notify(new ConfirmationCode('Action Required: Complete Your Email Verification on bouncee.net',[],'verification-action-required'));
        }

        
    }

}