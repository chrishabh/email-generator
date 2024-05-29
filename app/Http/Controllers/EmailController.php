<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmailController extends Controller
{
    public function generateEmail(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
        ]);

        $firstName = strtolower($request->input('first_name'));
        $lastName = strtolower($request->input('last_name'));
        $domain = strtolower($request->input('domain'));

        $possibleEmails = [
            "{$firstName}.{$lastName}@{$domain}",
            "{$firstName}{$lastName}@{$domain}",
            "{$firstName}_{$lastName}@{$domain}",
            "{$lastName}.{$firstName}@{$domain}",
            "{$lastName}{$firstName}@{$domain}",
            "{$lastName}_{$firstName}@{$domain}",
            substr($firstName,0,1)."{$lastName}@{$domain}",
            "{$lastName}".substr($firstName,0,1)."@{$domain}",
            "{$firstName}@{$domain}",
            "{$lastName}@{$domain}",
            substr($firstName,0,1).".{$lastName}@{$domain}",
            "{$lastName}.".substr($firstName,0,1)."@{$domain}",
            substr($firstName,0,1)."_{$lastName}@{$domain}",
            "{$lastName}_".substr($firstName,0,1)."@{$domain}",
            substr($lastName,0,1)."{$firstName}@{$domain}",
            "{$firstName}".substr($lastName,0,1)."@{$domain}",
            substr($lastName,0,1).".{$firstName}@{$domain}",
            "{$firstName}.".substr($lastName,0,1)."@{$domain}",
            substr($lastName,0,1)."_{$firstName}@{$domain}",
            "{$firstName}_".substr($lastName,0,1)."@{$domain}",
            "{$firstName}-{$lastName}@{$domain}",
            "{$lastName}-{$firstName}@{$domain}",
            substr($lastName,0,1). substr($firstName,0,1)."@{$domain}",
            substr($firstName,0,1). substr($lastName,0,1)."@{$domain}",



        ];

        $validEmails = [];
        foreach ($possibleEmails as $email) {
            if ($this->isValidEmail($email)) {
                $validEmails[] = $email;
            }
        }
        return back()->with('validEmails', $validEmails);
    }

    private function isValidEmail($email)
    {
        if(env('KICKBOX_API_FLAG')){
            $apiKey = env('KICKBOX_API_KEY'); // Replace with your Kickbox API key
            $response = Http::get("https://api.kickbox.com/v2/verify", [
                'email' => $email,
                'apikey' => $apiKey,
            ]);
    
            $data = $response->json();
    
            return isset($data['result']) && $data['result'] === 'deliverable';
        }else{
            return true;
        }
       
    }
}
