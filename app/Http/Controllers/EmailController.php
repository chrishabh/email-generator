<?php

namespace App\Http\Controllers;

use App\Imports\BulkUploadImport;
use App\Models\UserCredits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EmailController extends Controller
{
    public function generateEmail(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails())
            return redirect()->back()->withErrors($validator)->withInput();

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

        return redirect()->back()->with(compact('possibleEmails'));
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

    function uploadBulkData(Request $request){ 
        $request->validate([
            'bulkUploadData' => 'required|mimes:xlsx,xls',
        ]); 
        try {
            if(Excel::import(new BulkUploadImport, $request->file('bulkUploadData'))){
                return response()->json(['success' => 'data imported successfully!']);
            }
            else{
                return response()->json(['error' =>  'something went wrong with this.']);
            }
            // return redirect()->back()->with('success', 'Import successful!');

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
            // return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function singleEmailPage(Request $request){
        $creditPoint =0;
        $headerData = array(); 
        if(Auth::check()){ 
            $data = UserCredits::getCreditPoint(Auth::user()->id); 
            if($data){
                $creditPoint =$data->credits;
                
            }
        }
            
        $headerData['creditPoint'] = $creditPoint; 
        return view('verify.single')->with(compact('headerData'));
    }



}
