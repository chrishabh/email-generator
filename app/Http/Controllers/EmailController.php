<?php

namespace App\Http\Controllers;

use App\Imports\BulkUploadImport;
use App\Jobs\ExportVerifiedEmailsJob;
use App\Jobs\VerifyEmailsJob;
use App\Models\BulkUploadEmailFileData;
use App\Models\uploadedAndDownloadFileName;
use App\Models\UserCredits;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
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
        UserCredits::updateCreditsWhenEmailGetsVerify(Auth::user()->id,1);
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

    function bulkPage(Request $request){
        $creditPoint =0;
        $headerData = array(); 
        $fileData   = array();
        if(Auth::check()){ 
            $data = UserCredits::getCreditPoint(Auth::user()->id); 
            if($data){
                $creditPoint =$data->credits;
                
            }
            // $data = BulkUploadEmailFileData::getBulkData(Auth::user()->id);
            $userid= Auth::user()->id;

           $fileData = self::getDataOfFileWithState('',$userid);
        }
          
        $headerData['creditPoint'] = $creditPoint; 
        return view('verify.bulk')->with(compact('headerData','fileData'));
    }

    private function getDataOfFileWithState($fileId='',$userid){
        $data = uploadedAndDownloadFileName::getAllData($fileId,$userid );
        $fileData =array();
        if(!empty($data)){
            foreach($data as $key=>$value){  
                    $countOfValidAndInvalidEmails  =  BulkUploadEmailFileData::getCountOfValidAndInvalidEmails($value->id,$userid);
                    $collectionOfCount             =  collect($countOfValidAndInvalidEmails); 
                    $validEmailCount               =  $collectionOfCount->firstWhere('status', 'valid')['total_count'] ?? null;
                    $invalidEmailCount             =  $collectionOfCount->firstWhere('status', 'invalid')['total_count'] ?? null;
                    $fileNameWithExtension         =  basename($value->fileName); 
                    $fileName                      =  pathinfo($fileNameWithExtension, PATHINFO_FILENAME);  
                    $parts                         =  explode('_', $fileName);  
                    $fileName                      =  $parts[0];  
                    $fileExtension                 =  pathinfo($fileNameWithExtension, PATHINFO_EXTENSION); // Get file extension
                    $fileName                      =  $fileName.'...'.$fileExtension;
                    $dataArr['fileName']           =  $fileName;
                    $dataArr['created_at']         =  ($value->created_at)? Carbon::parse($value->created_at)->format('n/j/y, g:i A'):null; 
                    $dataArr['totalValidEmail']    =  $validEmailCount; 
                    $dataArr['totalInvalidEmail']  =  $invalidEmailCount; 
                    $dataArr['total']              =  $invalidEmailCount +$validEmailCount; 
                    $dataArr['verificationStatus'] =  $value->verificationStatus; 
                    $dataArr['userId']             =  $value->user_id; 
                    $dataArr['fileId']             =  $value->id; 
                    array_push($fileData,$dataArr);
            }
        }
        return $fileData;
    }
    function uploadBulkData(Request $request){       
        try { 

            $request->validate([
                'filepond' => 'required|file|mimes:csv,txt',
            ]);
             
            $file     = $request->file('filepond');
            $rowCount = 0;

            if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                $header = fgetcsv($handle); // Try to read the first line (header)
            
                if ($header === false) {
                    $response = response()->json(['error' => 'The file is not a valid CSV file.']);
                    $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
                    return $response;
                } 
                while (($row = fgetcsv($handle)) !== FALSE) {
                    $rowCount++;
                }
                fclose($handle);
            } 

            $userCredit = UserCredits::getCreditPoint(Auth::user()->id);
            $creditPoints = ($userCredit) ? $userCredit->credits :0;
            if ($rowCount > $creditPoints) {
                $response = response()->json(['error' => 'You should not have enough credit score to validate the email.']);
                $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
                return $response;
            }

            $handle            = fopen($file->getRealPath(), 'r');
            $currentDate       = Carbon::now()->format('Y-m-d');
            $timestamp         = Carbon::now()->format('Ymd_His');  
            $userId            = Auth::user()->id;
            // $userId            = 1;
            $originalFilename  = $file->getClientOriginalName();
            $originalFilename  = pathinfo($originalFilename, PATHINFO_FILENAME);
            $extension        = $request->file('filepond')->getClientOriginalExtension();
            $fileName         = "{$originalFilename}_{$userId}_{$timestamp}.{$extension}";
            $path             = "bulkUpload/{$currentDate}/{$userId}/{$fileName}";
            $instanceOfUp     = new uploadedAndDownloadFileName();


            // Read the CSV file line by line
            $insertArray = [];
            $fileId      = $instanceOfUp->insertDataAndgetId(array(
                'user_id'              =>  $userId,
                'fileName'             =>  $fileName,
                'uploadedFileLocation' =>  'public/'.$path,
                'created_at'           => Carbon::now()
               ));
            while (($row = fgetcsv($handle)) !== FALSE) {
                $arr = array();
                
                // Ensure the row is not empty and contains at least one column
                if (isset($row[0])) {
                    $email = mb_convert_encoding($row[0], 'UTF-8', 'auto');
                   if($fileId){
                        $arr   =  array(
                            'email'     => $email,
                            'file_id'    => $fileId,
                            'importedBy' => $userId,
                            'type'       =>'bulk', 
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ); 
                        array_push($insertArray,$arr);
                   }
                     
                   
                }
            }

            if(!empty($insertArray)){
                if(DB::table('bulk_upload_email_file_data')->insert($insertArray)){ 
                    $filePath         = $file->storeAs('public/',$path); 
                    // return back()->withErrors(['success' => 'data import successfully!','path'=>$filePath]); 
                    return response()->json(['success' => 'data import successfully!','path'=>$filePath])->header('Content-Type', 'application/json; charset=UTF-8');
                }else{
                    return response()->json(['error' => 'something went wrong while importing the data.'])->header('Content-Type', 'application/json; charset=UTF-8');
                    // return response()->json(['error' =>  'something went wrong while importing the data.']);
                }

            }
            

            // if(Excel::import(new BulkUploadImport($request->file('filepond')), $request->file('filepond'))){
            //     return response()->json(['success' => 'data imported successfully!']);
            // }
            // else{
            //     return response()->json(['error' =>  'something went wrong with this.']);
            // }
            // return redirect()->back()->with('success', 'Import successful!');

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->header('Content-Type', 'application/json; charset=UTF-8');
        }
    }

    function singleEmailPage(Request $request){
        $creditPoint =0;
        $headerData = array(); 
        if(Auth::check()){ 
            $data = UserCredits::getCreditPoint(Auth::user()->id); 
           
            if(!empty($data)){
                $creditPoint =$data->credits;
                
            }
        }
            
        $headerData['creditPoint'] = $creditPoint; 
        return view('verify.single')->with(compact('headerData'));
    }


    // function getAllData(Request $request){;

    //     // $userID = Auth::user()->id;
    //     $userID = 1;
    //     $data = uploadedAndDownloadFileName::getAllData($userID);
    //     return response()->json(['success' => 'data import successfully!','data'=>$data])->header('Content-Type', 'application/json; charset=UTF-8');
    // }

    function exportData(Request $request){
        $rules = [
            'fileId'    => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator])->header('Content-Type', 'application/json; charset=UTF-8');
        }

        $data =uploadedAndDownloadFileName::getDownloadPath($request['fileId'],Auth::user()->id);
        if($data){
            $filePath = storage_path('app/'.$data->uploadedFileLocation); 
            if (file_exists($filePath)) {
                return response()->download($filePath,'filename.csv', [
                    'Content-Type' => 'text/csv',
                ]);
            } else {
                return response()->json(['error' => 'File not found'], 404);
            }
        }else {
            return response()->json(['error' => 'File not found'], 404)->header('Content-Type', 'application/json; charset=UTF-8');
        }
    }


    function startVerification(Request $request){
        $rules = [
            'fileId'    => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator])->header('Content-Type', 'application/json; charset=UTF-8');
        }
        
        VerifyEmailsJob::dispatch($request['fileId']);
        return response()->json(['sucess'=>'ok','status'=>200,'data'=>self::getDataOfFileWithState($request['fileId'],Auth::user()->id)],200)->header('Content-Type', 'application/json; charset=UTF-8');

    }



}
