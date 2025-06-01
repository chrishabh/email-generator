<?php

namespace App\Services\Auth;

use App\Models\ReCaptchaLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ReCaptchaService
{
  
    public static function verifyAndLog($recaptchaResponse,?string $userIp = null,$signupLog): bool
    {
        // try {
            // $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            //     'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
            //     'response' => $recaptchaResponse,
            // ]);

            // $responseBody = $response->json();


            // Log the response in the database
            // $recaptcha_response_obj = [
            //     'success'      => $responseBody['success'] ?? false,
            //     'score'        => $responseBody['score'] ?? null,
            //     'action'       => $responseBody['action'] ?? null,
            //     'challenge_ts' => isset($responseBody['challenge_ts'])? Carbon::parse($responseBody['challenge_ts'])->format('Y-m-d H:i:s'): null,
            //     'hostname'     => $responseBody['hostname'] ?? null,
            //     'user_ip'      => $userIp,
            // ];

            // Update the log with the recaptcha_response
            // $signupLog->update([
            //     'recaptcha_response' => json_encode($recaptcha_response_obj), 
            // ]);

            // return isset($responseBody['success']) && $responseBody['success'];
            return true; // For testing purposes, always return true
        // } catch (\Throwable $th) {
        //     // pp($th->getMessage());
        //     \Illuminate\Support\Facades\Log::error('ReCAPTCHA verification failed: ' . $th->getMessage());
        //     return false;
        // }
    }
}
