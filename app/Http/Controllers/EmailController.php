<?php

namespace App\Http\Controllers;

use App\Imports\BulkUploadImport;
use App\Jobs\ExportVerifiedEmailsJob;
use App\Jobs\VerifyEmailsJob;
use App\Models\BulkUploadEmailFileData;
use App\Models\EmailVerificationLog;
use App\Models\Integration;
use App\Models\IntegrationTool;
use App\Models\LeadFinder;
use App\Models\LeadFinderPCEmailLogs;
use App\Models\singleVerification;
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
use PhpOffice\PhpSpreadsheet\IOFactory;

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

        $firstName              = strtolower($request->input('first_name'));
        $lastName               = strtolower($request->input('last_name'));
        $domain                 = strtolower($request->input('domain'));
        if (preg_match('/^(http:\/\/|https:\/\/|www\.)/', $domain)) { 
            $domain = preg_replace('/^(http:\/\/|https:\/\/|www\.)/', '', $domain);
            $domain = explode('/', $domain)[0];
        }
        $stopValidationCheckbox = strtolower($request->input('stopValidationCheckbox'));

        $possibleEmails = [
            "{$firstName}.{$lastName}@{$domain}",
            "{$firstName}{$lastName}@{$domain}",
            substr($firstName,0,1)."{$lastName}@{$domain}",
            "{$lastName}.".substr($firstName,0,1)."@{$domain}",
            "{$firstName}@{$domain}",
            "{$lastName}@{$domain}",
            substr($firstName,0,1).".{$lastName}@{$domain}",
            "{$firstName}".substr($lastName,0,1)."@{$domain}",
            "{$firstName}.".substr($lastName,0,1)."@{$domain}",
            substr($firstName,0,1). substr($lastName,0,1)."@{$domain}", 
            substr($firstName,0,1).".". substr($lastName,0,1)."@{$domain}", 
            "{$lastName}{$firstName}@{$domain}",
            "{$lastName}.{$firstName}@{$domain}",
            "{$lastName}.". substr($firstName,0,1)."@{$domain}",
            substr($lastName,0,1)."{$firstName}@{$domain}",
            substr($lastName,0,1).".{$firstName}@{$domain}",
            substr($lastName,0,1). substr($firstName,0,1)."@{$domain}",
            substr($lastName,0,1).".".substr($firstName,0,1)."@{$domain}",
            "{$firstName}-{$lastName}@{$domain}",
            substr($firstName,0,1)."-{$lastName}@{$domain}",
            "{$firstName}-".substr($lastName,0,1)."@{$domain}",
            substr($firstName,0,1)."-". substr($lastName,0,1)."@{$domain}", 
            "{$lastName}-{$firstName}@{$domain}",
            "{$lastName}-".substr($firstName,0,1)."@{$domain}",
            substr($lastName,0,1)."-{$firstName}@{$domain}", 
            substr($lastName,0,1)."-".substr($firstName,0,1)."@{$domain}",
            "{$firstName}_{$lastName}@{$domain}",
            "{$lastName}_{$firstName}@{$domain}",
            substr($firstName,0,1)."_{$lastName}@{$domain}",
            "{$firstName}_".substr($lastName,0,1)."@{$domain}",
            substr($firstName,0,1)."_". substr($lastName,0,1)."@{$domain}", 
            "{$lastName}_".substr($firstName,0,1)."@{$domain}",
            substr($lastName,0,1)."_{$firstName}@{$domain}",
            substr($lastName,0,1)."_".substr($firstName,0,1)."@{$domain}",
        ];

        $validEmails = [];
        $leadFinderArray = [
            'firstName'          => $firstName,
            'lastName'           => $lastName,
            'domain'             => $domain,
            'isValidationPause'  => $stopValidationCheckbox,
            'user_id'            => Auth::user()->id
        ];

        $leadFind                = new leadFinder();
        $lastLeadId              = $leadFind->insertDataAndgetId($leadFinderArray);
        // $lastLeadId =4;
        $leadPCEmailLogsArray    = array();
        $count                   = 0;
        $isFirstValidEmailFound  =  false;
        $index                   = null;
        foreach ($possibleEmails as $key => $email) {
            $dataArray =array();
            $dataArray['email']          = $email;
            $dataArray['lead_finder_id'] = $lastLeadId;
            $dataArray['status']         = NULL;
            array_push($leadPCEmailLogsArray,$dataArray);
        }

        
        $logsPCTable = new LeadFinderPCEmailLogs();
        $logsPCTable->insertDataAndgetId($leadPCEmailLogsArray);

        $validEmails = LeadFinder::with('leadFinderPCEmailLogs')->find($lastLeadId)->toArray();
        // $response = response()->json(['success'=>'ok','result'=>$validEmails],200);
        // $response->headers->set('Content-Type', 'application/json; charset=UTF-8');

             
        return redirect()->back()->with(compact('validEmails'))->withInput();
        // return $response;
    }

    public function emailVerification(Request $request){
        try{
            $rules = [
                'fileId'             => 'required',
                'isValidationPause'  => 'required',
                'emailId'            => 'required',
            ];
    
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){
                $response = response()->json(['success'=>'ok','error'=>$validator],401);  
                $response->headers->set('Content-Type', 'application/json; charset=UTF-8'); 
                return $response;
            }
    
            $fileId                  = strtolower($request->input('fileId'));
            $stopValidationCheckbox  = strtolower($request->input('isValidationPause'));
            $emailId                 = strtolower($request->input('emailId'));
            $data                    = LeadFinderPCEmailLogs::getEmailDataBasedOnId($emailId,$fileId);
            $status                  = null;
            $isAbortAll              = false;
            $user_id                 = Auth::user()->id;
            $userCredit              = UserCredits::getCreditPoint($user_id);
             $creditPoints            = ($userCredit) ? $userCredit->credits :0;
            // if($creditPoints<1) return response()->json(['success'=>false,'error' =>'You should not have enough credit score to validate the email.'])->header('Content-Type', 'application/json; charset=UTF-8');
           
            if(!empty($data)){
                $email = $data['email'];
                $id    = $data['id'];
                if($stopValidationCheckbox=='0'){
                    if($this->isValidEmail($email,false,$user_id,$fileId)){
                        $status= 'valid';
                    }else{
                        $status = 'invalid';
                    }
                    UserCredits::updateCreditsWhenEmailGetsVerify($user_id,1);
                }
                if ($stopValidationCheckbox=='1'){
                    if($this->isValidEmail($email,false,$user_id,$fileId)){ 
                        $status = 'valid';
                   }else{
                        $status = 'invalid';
                   } 
                   UserCredits::updateCreditsWhenEmailGetsVerify($user_id,1);
                }
    
                $ob1 = new LeadFinderPCEmailLogs; 
                if($ob1->insertDataAndgetId(['status'=>$status],$id)){
                    if($status=='valid' && $stopValidationCheckbox=='1'){
                        $toId = LeadFinderPCEmailLogs::getLastIdOfPCTable($fileId);
                        if($id!=null){
                            if(LeadFinderPCEmailLogs::whereBetween('id', [$id, $toId])->update([ 'status' => 'aborted'])){
                                $isAbortAll = true;
                            }
                        }
                    }
                }  

                return response()->json(['result'=>['creditPoint'=>$creditPoints??0,'status'=>$status,'isAbortAll' => $isAbortAll , 'emailId' => $emailId , 'fileId' =>$fileId ]])->header('Content-Type','application/json; charset=UTF-8');
            }
        }catch(\Exception $e){ 
            \Illuminate\Support\Facades\Log::error('lead finder error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage(),'status'=>'ok'])->header('Content-Type', 'application/json; charset=UTF-8');  
        }
         

    }

    public function testThirdPartyAPI(){
        return getDebounceCreditBalance();
        $this->isValidEmail("ch.rishabh8527@gmail.com");
    }

    public static function isValidEmail($email,$get_response = false, $user_id=null,$fileId=null)
    {
        if(env('API_PLATFORM') == "bouncify"){
            if(env('KICKBOX_API_FLAG',false)){

                $data = singlebouncify($email,$fileId);
                
                $log = [
                    'user_id' => Auth::User()->id??$user_id,
                    'email' => $email,
                    'result' => json_encode($data),
                    'created_at'=>Carbon::now()
                ];
                EmailVerificationLog::addLog($log);
                if($get_response){
                    return $data['result'];
                }
                return isset($data['result']) && $data['result'] === 'deliverable';
            }else{
                $log = [
                    'user_id' => Auth::User()->id??$user_id,
                    'email' => $email,
                    'result' => "Bouncify Flag off.",
                    'created_at'=>Carbon::now()
                ];
                EmailVerificationLog::addLog($log);
                return false;
            }

        }elseif(env('API_PLATFORM') == "debouncee"){
            if(env('KICKBOX_API_FLAG',false)){
                $apiKey = env('KICKBOX_API_KEY'); // Replace with your Kickbox API key
                $response = Http::get('https://api.debounce.io/v1/', [
                    // 'query' => [
                        'api' => $apiKey,
                        'email' => $email,
                    // ]
                ]);

                // Extract response body and HTTP status code
                $responseBody = $response->json();
                $httpcode     = $response->status();

                $logData = [
                    'job_id'            =>  'GET',
                    'file_id'           =>  $fileId,
                    'which_api'         => 'DEBOUNCE_EMAIL_VERIFY_API',
                    'url'               => 'https://api.debounce.io/v1/',
                    'request'           =>json_encode(['email' => $email, 'key' => $apiKey]), // Store request data
                    'response'          => json_encode($responseBody), 
                    'api_status_code'   => $httpcode,
                    'created_at'        => now()
                ];
            
                // Insert log with null job_id
                $logId    = DB::table('bulk_api_request_response_logs')->insertGetId($logData);
            
                $data = $response->json();
                $log = [
                    'user_id' => Auth::User()->id??$user_id,
                    'email' => $email,
                    'result' => json_encode($data),
                    // 'created_at'=>Carbon::now()
                ];
                EmailVerificationLog::addLog($log);
                if($get_response){
                    return $data['debounce']['reason'];
                }
                return isset($data['debounce']['reason']) && $data['debounce']['reason'] === 'Deliverable';
            }else{
                $log = [
                    'user_id' => Auth::User()->id??$user_id,
                    'email' => $email,
                    'result' => "Debouncee Flag off.",
                     'created_at'=>Carbon::now()
                ];
                EmailVerificationLog::addLog($log);
                return false;
            }
        }elseif(env('API_PLATFORM')=='bouncee'){
            $apiUrl = envparam('BOUNCEE_API_URL');
            $apiKey = envparam('BOUNCEE_API_KEY');
            $response = Http::withHeaders([
                    'X-API-KEY' => $apiKey,
                    'Accept' => 'application/json',
                ])->get("$apiUrl=$email");
                // Extract response body and HTTP status code
                $responseBody = $response->json();
                $httpcode     = $response->status();

                $logData = [
                    'job_id'            =>  'GET',
                    'file_id'           =>  $fileId,
                    'which_api'         => 'BOUNCEE_API',
                    'url'               => $apiUrl,
                    'request'           =>json_encode(['email' => $email]), // Store request data
                    'response'          => json_encode($responseBody), 
                    'api_status_code'   => $httpcode,
                    'created_at'        => now()
                ];
            
                // Insert log with null job_id
                //$logId    = DB::table('bulk_api_request_response_logs')->insertGetId($logData);
            
                $data = $response->json();
                $log = [
                    'user_id' => Auth::User()->id??$user_id,
                    'email' => $email,
                    'result' => json_encode($data),
                    // 'created_at'=>Carbon::now()
                ];
                EmailVerificationLog::addLog($log);
                if($get_response){
                    return $data['status']??'Unknown';
                }
                return isset($data['status']) && $data['status'] === 'Deliverable'; 

        }else{
            $log = [
                'user_id' => Auth::User()->id??$user_id,
                'email' => $email,
                'result' => "API Flag off.",
                 'created_at'=>Carbon::now()
            ];
            EmailVerificationLog::addLog($log);
            return false;
        }
      
       
    }

    function bulkPage(Request $request){
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
          
        $headerData['creditPoint'] = $creditPoint??0; 
        return view('verify.bulk')->with(compact('headerData','fileData'));
    }
    
    function searchBar(Request $request){
        $searchBar = $request->input('searchContent');
        $isReset   = $request->input('isReset');
        $fileData  = array();
        $userid    = Auth::user()->id;
        if(isset($searchBar) && !empty($searchBar)){
            $searContent  = $request->input('searchContent');
            $fileData  = self::getDataOfFileWithState('',$userid,$searContent);
        }
        if(isset($isReset) && !empty($isReset) && $isReset) $fileData   = self::getDataOfFileWithState('',$userid);
        $html = view('partials.file_list', compact('fileData'))->render(); 
        $response  = response()->json(["success"=>"ok",'html' => $html],200); 
        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
        return $response;
    }

    private function getDataOfFileWithState($fileId='',$userid,$searchContent=''){
        $data = uploadedAndDownloadFileName::getAllData($fileId,$userid,$searchContent );
        $fileData =array();
        if(!empty($data)){
            foreach($data as $key=>$value){
                $iconUrl = null;
                if($value->is_tools_integerate_email=='1'){ 
                    $integrated = Integration::with('tool')->where('status','verified')->where('id',$value->integeration_id)->whereNull('deleted_at')->first(); 
                    if($value->tool_id){
                        $iconUrl    = IntegrationTool::select('icon')->where('id',$value->tool_id)->first()->icon_url;
                    }
                     
                    // if($integrated)
                    // $iconUrl = $integrated['tool']['icon_url'];
                }
                    $countOfValidAndInvalidEmails         =  BulkUploadEmailFileData::getCountOfValidAndInvalidEmails($value->id,$userid);
                    // pp($countOfValidAndInvalidEmails);
                    $collectionOfCount                    =  collect($countOfValidAndInvalidEmails)->sum('total_count');
                    // $validEmailCount               =  $collectionOfCount->firstWhere('status', 'valid')['total_count'] ?? 0;
                    // $invalidEmailCount             =  $collectionOfCount->firstWhere('status', 'invalid')['total_count'] ?? 0;
                    $fileNameWithExtension                =  basename($value->fileName); 
                    $fileName                             =  pathinfo($fileNameWithExtension, PATHINFO_FILENAME);  
                    $parts                                =  explode('_', $fileName);  
                    $fileName                             =  $parts[0];  
                    $fileExtension                        =  pathinfo($fileNameWithExtension, PATHINFO_EXTENSION); // Get file extension
                    $dataArr['fileName']                  =  ($value->is_tools_integerate_email=='1' &&  $value->tool_name) ? $value->tool_name:$fileName.'...'.$fileExtension;
                    // $fileName                      =  $fileName.'...'.$fileExtension;
                    $dataArr['iconURL']                   =  $iconUrl; 
                    $dataArr['is_tools_integerate_email'] = $value->is_tools_integerate_email; 
                    $dataArr['created_at']                =  ($value->created_at)? Carbon::parse($value->created_at)->format('n/j/y, g:i A'):null; 
                    // $dataArr['totalValidEmail']    =  $validEmailCount; 
                    $dataArr['verifyStatusData']          =  $countOfValidAndInvalidEmails;
                    $dataArr['total']                     =  $collectionOfCount??0; 
                    $dataArr['verificationStatus']        =  $value->verificationStatus; 
                    $dataArr['userId']                    =  $value->user_id; 
                    $dataArr['fileId']                    =  $value->id; 
                    $dataArr['isDownloadFileLocation']    =  (empty($value->downloadFileLocation) ||  ($value->downloadFileLocation==null) ) ? '0' : '1'; 
                    $dataArr['toolName']                  =  $value->tool_name; 
                    array_push($fileData,$dataArr);
            }
        }
        return $fileData;
    }
    function uploadBulkData(Request $request){       
        try { 

            $request->validate([
                'filepond' => 'required|file|mimes:csv,txt,xlsx,xls',
            ]);
             
            $file         = $request->file('filepond');
            $extension    = $file->getClientOriginalExtension();
            $rowCount     = 0;
            $uniqueEmails = [];
            if (in_array($extension, ['csv', 'txt'])) {
                if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
                    $header = fgetcsv($handle); // Try to read the first line (header)
                    if ($header === false) {
                        $response = response()->json(['error' => 'Invalid CSV/TXT file format.']);
                        $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
                        return $response;
                    } 
                    while (($row = fgetcsv($handle)) !== FALSE) {
                        $rowCount++;
                        // Ensure the row is not empty and contains at least one column
                        if (isset($row[0])) {
                            $email = mb_convert_encoding($row[0], 'UTF-8', 'auto');
                            // Add only valid and unique emails to the array
                            if (filter_var($email, FILTER_VALIDATE_EMAIL) && !in_array($email, $uniqueEmails)) {
                                $uniqueEmails[] = $email;
                            }
                        }
                    }
                    fclose($handle);
                } 
            }elseif (in_array($extension, ['xlsx', 'xls'])) {
                $spreadsheet = IOFactory::load($file->getRealPath());
                $sheet       = $spreadsheet->getActiveSheet();
                foreach ($sheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    foreach ($cellIterator as $cell) {
                        $email = trim($cell->getValue());
                        if (filter_var($email, FILTER_VALIDATE_EMAIL) && !in_array($email, $uniqueEmails)) {
                            $uniqueEmails[] = $email;
                        }
                    }
                }
            }
            if (empty($uniqueEmails)) {
                $response = response()->json(['error' => 'No valid emails found in the file.']);
                $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
                return $response;
            }

            // $userCredit = UserCredits::getCreditPoint(Auth::user()->id);
            // $creditPoints = ($userCredit) ? $userCredit->credits :0;
            // if (count($uniqueEmails) > $creditPoints){
            //     $response = response()->json(['error' => 'You should not have enough credit score to validate the email.']);
            //     $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
            //     return $response;
            // }

            $handle            = fopen($file->getRealPath(), 'r');
            $currentDate       = Carbon::now()->format('Y-m-d');
            $timestamp         = Carbon::now()->format('Ymd_His');  
            $userId            = Auth::user()->id;
            // $userId            = 1;
            $originalFilename  = $file->getClientOriginalName();
            $originalFilename  = pathinfo($originalFilename, PATHINFO_FILENAME);
            $extension        = $request->file('filepond')->getClientOriginalExtension();
            $fileName         = "{$originalFilename}_bounce_{$userId}_{$timestamp}.{$extension}";
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

            foreach ($uniqueEmails as $email) { 
                // Only add the email if it is unique in the database as well
                if($fileId){
                    $insertArray[] = [
                        'email'     => $email,
                        'file_id'   => $fileId,
                        'importedBy'=> $userId,
                        'type'      => 'bulk', 
                        'status'    => NULL,
                        'created_at'=> Carbon::now()
                    ];
                }
                 
            }

            // pp($insertArray);
            if(!empty($insertArray)){
                if(DB::table('bulk_upload_email_file_data')->insert($insertArray)){ 
                    $filePath         = $file->storeAs('public/',$path); 
                    // return back()->withErrors(['success' => 'data import successfully!','path'=>$filePath]); 
                    return response()->json(['success' => 'File imported successfully!','path'=>$filePath])->header('Content-Type', 'application/json; charset=UTF-8');
                }else{
                    return response()->json(['error' => 'something went wrong while importing the data.'])->header('Content-Type', 'application/json; charset=UTF-8');
                    // return response()->json(['error' =>  'something went wrong while importing the data.']);
                }
            }
        

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->header('Content-Type', 'application/json; charset=UTF-8');
        }
    }

    function singleEmailPage(Request $request){
        $headerData = array(); 
        if(Auth::check()){ 
            $userId               = Auth::user()->id;
            $data                 = UserCredits::getCreditPoint($userId); 
            $oldVerificationData  = singleVerification::where('user_id', $userId)->orderBy('id', 'desc')->get()->toArray();
            if(!empty($data)){
                $creditPoint =$data->credits;
                
            }
        }
        $headerData['creditPoint']         = $creditPoint??0; 
        $headerData['oldVerificationData'] = $oldVerificationData; 
        return view('verify.single')->with(compact('headerData'));
    }


    function leadFinder(Request $request){
        $headerData = array(); 
        if(Auth::check()){ 
            $data = UserCredits::getCreditPoint(Auth::user()->id); 
           
            if(!empty($data)){
                $creditPoint =$data->credits;
                
            }
        }
            
        $headerData['creditPoint'] = $creditPoint??0; 
        return view('verify.leadFindler')->with(compact('headerData'));   
    }

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
            $filePath = storage_path('app/'.$data->downloadFileLocation); 
            if (file_exists($filePath)) {
                return response()->download($filePath,'filename.csv', [
                    'Content-Type' => 'text/csv',
                    'filename' => $data->downloadFileName,
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
        $userId       = Auth::user()->id;
        $totalEmails  = BulkUploadEmailFileData::getCountOfEmails($request['fileId'],$userId);
       
        if(!(UserCredits::where('plan_type','Unlimited')->where('user_id',$userId)->whereNull('deleted_at')->orderBy('id', 'desc')->exists())){
            $userCredit   = UserCredits::getCreditPoint($userId);
            $creditPoints = ($userCredit) ? $userCredit->credits :0;
            if($creditPoints<$totalEmails) return response()->json(['success'=>false,'message' =>'You don’t have enough credits to validate '. $totalEmails.' email.'])->header('Content-Type', 'application/json; charset=UTF-8');

        }
        VerifyEmailsJob::dispatch($request['fileId'],$userId);
        return response()->json(['sucess'=>true,'status'=>200,'data'=>self::getDataOfFileWithState($request['fileId'],$userId)],200)->header('Content-Type', 'application/json; charset=UTF-8');

    }

    private static function verifyEmailByBouncifyJob($fileId){ 
        $user_id = Auth::user()->id;
        $data    = uploadedAndDownloadFileName::getDataFromTable($fileId,$user_id,'pending');
        if(!empty($data)){
            $uploadFileLocation  =  storage_path('app/'.$data['uploadedFileLocation']); 
            if(!empty($uploadFileLocation)){
                $response  = bulkBouncify($uploadFileLocation,$fileId);
                if($response && $response['success']){
                    $job_id = $response['job_id'];
                    echo $job_id;
                    if(uploadedAndDownloadFileName::updateData(['job_id'=>$job_id],$fileId)){
                        $interval               = 10; //check every $interval second
                        $isVerficationCompleted = false;
                        do {
                            // Call the bulkJobStatus function
                            $response = bulkJobStatus($job_id, $fileId);
                
                            // Log the response and status
                            if ($response['success']) {
                                $status = $response['status'];
                                // Check if the job is completed
                                if ($status == 'completed') {
                                    $isVerficationCompleted =true;
                                    break;
                                }
                            } else {
                                $isVerficationCompleted = false;
                                break;
                            }
                
                            // Wait for the specified interval before checking again
                            sleep($interval);
                
                        } while (true);


                        if($isVerficationCompleted){
                            $response  = bulkDownload($job_id,$fileId);
                            if($response && $response['success']){
                                 
                                $lines = explode("\n", $response['data']);

                                // Initialize an array to hold the result
                                $result = [];
                                
                                // Loop through each line
                                foreach ($lines as $line) {
                                    // Parse the line as CSV
                                    $result[] = str_getcsv($line);
                                }
                                print_r($result);
                            }
                        }
                    }
                }
                return $response;
                 
            }
        }

    }



    function checkJobStatusPeriodically($job_id, $fileId, $interval = 10)
    {
        do {
            // Call the bulkJobStatus function
            $response = bulkJobStatus($job_id, $fileId);

            // Log the response and status
            if ($response['success']) {
                $status = $response['status'];
                echo "Current Status: $status\n";

                // Check if the job is completed
                if ($status == 'completed') {
                    echo "Job completed successfully!\n";
                    break;
                }
            } else {
                echo "Error: " . $response['message'] . "\n";
                break;
            }

            // Wait for the specified interval before checking again
            sleep($interval);

        } while (true);
    }

    function checkEmailIsValidInvalid(Request $request){
        // try {
            $rules = [
                'domain'   => 'required|email'
            ];
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails())
                return redirect()->back()->withErrors($validator)->withInput();
                
            $email =  $request->input('domain'); 
            $status = $this->isValidEmail($email,true);
            $arrayData =[
                'email'   => $email,
                'user_id' => Auth::user()->id,
                'status'  => $status ? $status:NULL
            ];
            UserCredits::updateCreditsWhenEmailGetsVerify($arrayData['user_id'],1);
            $singleVerification = new singleVerification;
            $singleVerification->insertDataAndgetId($arrayData);
            $validEmails = $arrayData;
            return redirect()->back()->with(compact('validEmails'))->withInput(); 
           

        // } catch (\Exception $e) {
        //     return redirect()->back()->withErrors($e->getMessage())->withInput();
        // }
    }

    function checkEmailVerificationStatus(Request $request){
        try{
            $rules = [
                'fileId'   => 'required'
            ];
            $validator = Validator::make($request->all(),$rules);
            if($validator->fails()){
                return response()->json([
                    'success'  => false,
                    'message'  => $validator
                ]);
            } 

            return response()->json([
                'success' => true,
                'data'    => uploadedAndDownloadFileName::getStatusOfEmailVerification($request->fileId),
                'message' => 'verification started...'
            ]);
        }catch(\Exception $e){
            \Illuminate\Support\Facades\Log::error('Error in renderSettingPage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ])->header('Content-Type', 'application/json; charset=UTF-8');
        }
    }

    public static function smtpHandshake(Request $request)
    {
        $email = $request['email'];
        $domain = substr(strrchr($email, "@"), 1); // Extract domain
        $mxRecords = dns_get_record($domain, DNS_MX);

        if (empty($mxRecords)) {
            return "No MX records found for domain $domain.";
        }

        // Use the highest priority MX server
        usort($mxRecords, function ($a, $b) {
            return $a['pri'] - $b['pri'];
        });
        $mxHost = $mxRecords[0]['target'];

        // Connect to the SMTP server
        $connection = fsockopen($mxHost, 25, $errno, $errstr, 10);
        if (!$connection) {
            return "Failed to connect to SMTP server: $errstr ($errno)";
        }

        // Perform SMTP handshake
        $responses = [];
        fwrite($connection, "HELO " . gethostname() . "\r\n");
        $responses[] = fgets($connection, 1024);

        // Specify the sender email
        fwrite($connection, "MAIL FROM: <test@example.com>\r\n");
        $responses[] = fgets($connection, 1024);

        // Specify the recipient email
        fwrite($connection, "RCPT TO: <$email>\r\n");
        $response = fgets($connection, 1024);
        $responses[] = $response;

        // Close the connection
        fwrite($connection, "QUIT\r\n");
        fclose($connection);

        // Check the response for recipient validation
        if (strpos($response, '250') !== false) {
            return "Email address is valid.";
        } elseif (strpos($response, '550') !== false) {
            return "Email address is invalid.";
        }

        return "Unable to verify the email address.";
    }
    


}
