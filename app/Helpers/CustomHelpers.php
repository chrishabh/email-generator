<?php

use App\Models\User;
use App\Notifications\ConfirmationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

function pp($arr, $die = "true")
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
    if ($die == 'true') {
        die();
    }
}

function generatePassword($length = 12) {
    // Define the allowed characters for the password
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    $password = '';
    
    // Generate a random password
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $password;
}

function isPasswordUsed($password) {
    // Fetch all hashed passwords from the User model
    $hashedPasswords = User::pluck('password')->toArray();

    // Check if the password (hashed) exists in the database
    foreach ($hashedPasswords as $hashedPassword) {
        if (Hash::check($password, $hashedPassword)) {
            return true; // Password already used
        }
    }

    return false; // Password is not used
}

function generateUniquePassword() {
    // Keep generating a password until a unique one is found
    do {
        $password = generatePassword();
    } while (isPasswordUsed($password));

    return $password; // Return the unique password
}

function logo_url(){
    return url('assets/logo.png');
}


function envparam($key = null, $default = null)
{
    if (empty($key)) {
        return config($key, $default);
    }

    $configVal = config("env." . $key, $default);
 
    return $configVal;
}

function bulkBouncify($path,$fileId){
    $apiKey = envparam('bulk_bouncify_key'); 
    $curl   = curl_init(); 
    $url    = "https://api.bouncify.io/v1/bulk?apikey=$apiKey";
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('local_file'=> new CURLFILE($path)),
    ));
    
    $response = curl_exec($curl);
    
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    // Log data in database
    $logData = [
        'job_id'            => null,
        'file_id'           => $fileId,
        'file_name'         => basename($path),
        'which_api'         => 'BOUNCIFY_BULK_GET_JOB_ID_API',
        'url'               => $url,
        'request'           => json_encode(['local_file' => basename($path)]), // Store request data
        'response'          => $response, // Store response data
        'api_status_code'   => $httpcode, // Store response data
        'created_at'        => now()
    ];
    // Insert log with null job_id
    $logId = DB::table('bulk_api_request_response_logs')->insertGetId($logData);

    if($httpcode == 201){ 
        $return            = json_decode($response, true);
        if($return['job_id']){
            $logData['job_id'] = $return['job_id']; // Store the job_id from response

            // Update the log with job_id
            DB::table('bulk_api_request_response_logs')->where('id', $logId)->update(['job_id' => $return['job_id']]); 
            
            sleep(10);
            $responsePatch     = patchBulkBouncify($return['job_id'],$fileId);
            if($responsePatch['success']){
                $customResponse['success']  =  true;
                $customResponse['httpCode'] =   $httpcode;
                $customResponse['message']  =  $return['message'];
                $customResponse['job_id']   =  $return['job_id'];
            }else{
                $customResponse             = $responsePatch;
                $customResponse['job_id']   = $return['job_id'];
            }   
        }  

    }else{
        $customResponse['success']      =   false;
        $customResponse['httpCode']     =   $httpcode;
        $customResponse['message']      =  'Something went Wrong with creating the jobid api!';
    }

    return $customResponse;
// Response example.
// {
//     "job_id": "r374aki32rnatv868nntpxloc7dkilszc3eu",
//     "success": true,
//     "message": "Bulk email verification list has been created"
//   }

}

function patchBulkBouncify($job_id,$fileId)
{
    $apiKey  = envparam('bulk_bouncify_key'); 
    $curl    = curl_init();
    $url     = "https://api.bouncify.io/v1/bulk/$job_id?apikey=$apiKey";
    curl_setopt_array($curl, array(

    CURLOPT_URL =>  $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'PATCH',
      CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
    CURLOPT_POSTFIELDS => json_encode(['action' => 'start']),
    ));

    $response = curl_exec($curl); 
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    
    // Log data in database
    $logData = [
        'job_id'            => $job_id,
        'file_id'           => $fileId,
        'which_api'         => 'BOUNCIFY_BULK_PATCH_API',
        'url'               => $url,
        'request'           => json_encode(['action' => 'start']), // Store request data
        'response'          => $response, // Store response data
        'api_status_code'   => $httpcode, // Store response data
        'created_at'        => now()
    ];

    // Insert log with null job_id
    $logId    = DB::table('bulk_api_request_response_logs')->insertGetId($logData);
    $response = json_decode($response, true);
    if($httpcode == 200){ 
        $customResponse =  $response;

    }else{
        $customResponse['success']  = false;
        $customResponse['httpCode'] = $httpcode;
        $customResponse['message']  = $response['result']??'Something went Wrong with creating the patch api!';
       
    } 
     
    return $customResponse;
    // {
    //     "job_id": "r374aki32rnatv868nntpxloc7dkilszc3eu",
    //     "success": true,
    //     "message": "Job verification will be attempted shortly. Call /status endpoint to know the status of the Job"
    // }

}

function bulkJobStatus($job_id,$fileId)
{
    $apiKey  = envparam('bulk_bouncify_key');
    $curl    = curl_init();
    $url     = "https://api.bouncify.io/v1/bulk/$job_id?apikey=$apiKey";

    curl_setopt_array($curl, array(
    // Replace jobId with your list's jobId and API_KEY with your API Key
    CURLOPT_URL => $url, 
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // Prepare log data
    $logData = [
        'job_id'     => $job_id, 
        'file_id'    => $fileId,
        'which_api'  => 'BOUNCIFY_JOB_STATUS_API',
        'url'        => $url,
        'request'    => null,  // No request body for GET
        'response'   => $response, // Store the response data
        'created_at' => now(),
    ];
    curl_close($curl);
    
    // Insert log with null job_id
    $logId = DB::table('bulk_api_request_response_logs')->insertGetId($logData);

    if($httpcode == 200){
        $response  = json_decode($response, true);
        if($response){
            $customResponse['success']  =  true;
            $customResponse['httpcode'] =  $httpcode;
            $customResponse['message']  =  $response['message'];
            $customResponse['status']   =  $response['status'];
            $customResponse['data']     =  $response;
        }

    }else{
        $customResponse['success']  =  false;
        $customResponse['httpcode'] =  $httpcode;
        $customResponse['status']   =   'ok';
        $customResponse['message']  = 'Something went Wrong with job status api !';
    }

    return $customResponse;

    // {
    //     "job_id": "r374aki32rnatv868nntpxloc7dkilszc3eu",
    //     "status": "completed",
    //     "created_at": "08/13/2021, 9:39:37 AM",
    //     "total": 2,
    //     "pending": 0,
    //     "verified": 2,
    //     "analysis": {
    //       "common_isp": 1,
    //       "role_based": 1,
    //       "disposable": 0,
    //       "spamtrap": 0,
    //       "syntax_error": 0
    //     },
    //     "results": {
    //       "deliverable": 1,
    //       "undeliverable": 0,
    //       "accept_all": 1,
    //       "unknown": 0
    //     },
    //     "success": true,
    //     "message": "Verification completed successfully. Please download the result using /download endpoint"
    //   }

}

function bulkDownload($job_id,$fileId)
{

    $apiKey  = envparam('bulk_bouncify_key');
    $curl    = curl_init();
    $url     = "https://api.bouncify.io/v1/download?jobId=$job_id&apikey=$apiKey";
    curl_setopt_array($curl, array(
    // Replace API_KEY with your API Key and jobId with your list's jobId, you need to download
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    ));

    $response = curl_exec($curl); 
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
    // Prepare log data
    $logData = [
        'job_id'     => $job_id,
        'file_id'    => $fileId,
        'which_api'  => 'BOUNCIFY_DOWNLOAD_API',
        'url'        => $url,
        'request'    => null,  // No request body for GET
        'response'   => $response, // Store the response data
        'created_at' => now(),
    ];
    curl_close($curl);
    
    // Insert log with null job_id
    $logId = DB::table('bulk_api_request_response_logs')->insertGetId($logData);

    if($httpcode == 200){
        $customResponse['success']  =  true;
        $customResponse['httpcode'] =  $httpcode;
        $customResponse['data']     =  $response;
        deleteBulkJob($job_id,$fileId);

    }else{
        $customResponse['success']  =  false;
        $customResponse['httpcode'] =  $httpcode;
        $customResponse['status']   =   'ok';
        $customResponse['message']  = 'Something went Wrong with download api !';
    }
 
    return $customResponse;

//     "Email", "Verification Result",	"Syntax Error",	"ISP", "Role", "Accept-all", "Disposable", "Trap", "Verified At"
// "info@sample.com", "deliverable", "N", "Y", "N", "N", "N", "2021-09-13T06:49:39.280Z"
// "support@sample.in", "accept-all", "N",	"N", "Y", "Y", "N", "2021-09-13T06:49:39.282Z"

}

function deleteBulkJob($job_id,$fileId)
{

    $apiKey  =  envparam('bulk_bouncify_key');
    $curl    =  curl_init();
    $url     =  "https://api.bouncify.io/v1/bulk/$job_id?apikey=$apiKey";
    curl_setopt_array($curl, array(
    // Replace jobId with your list's jobId and API_KEY with your API Key,you needs to delete
    CURLOPT_URL =>  $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'DELETE',
    ));

    $response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    // Prepare log data
    $logData = [
        'job_id'     => $job_id,
        'file_id'    => $fileId,
        'which_api'  => 'BOUNCIFY_DELETE_JOB_API',
        'url'        => $url,
        'request'    => null,  // No request body for GET
        'response'   => $response, // Store the response data
        'created_at' => now(),
    ];
    
    curl_close($curl);

    // Insert log with null job_id
    $logId = DB::table('bulk_api_request_response_logs')->insertGetId($logData);
    $response  =  json_decode($response,true);

    if($httpcode == 200){ 
        $customResponse['success']  =  true;
        $customResponse['httpCode'] =   $httpcode;
        $customResponse['message']  =  $response['message']; 

    }else{
        $customResponse['success']  =  false;
        $customResponse['httpCode'] =  $httpcode;
        $customResponse['message']  =   $response['message']?? 'Something went Wrong with delete api !'; 
    }

    return $customResponse;
}


function singlebouncify($email,$fileId=NULL){
    $apiKey = envparam('bouncify_key');
    $curl = curl_init();
    $url  = "https://api.bouncify.io/v1/verify?apikey=$apiKey&email=$email";
    curl_setopt_array($curl, array(
    // Replace API_KEY with your API Key
    CURLOPT_URL =>  $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE); 
    $curlError = curl_error($curl);
    curl_close($curl); 

       // Log data in database
    $logData = [
        'job_id'            => 'GET',
        'file_id'           => $fileId,
        'which_api'         => 'BOUNCIFY_EMAIL_VERIFY_API',
        'url'               => $url,
        'request'           =>json_encode(['email' => $email,'key'=>$apiKey]), // Store request data
        'response'          => $response ?: $curlError, // Store response data
        'api_status_code'   => $httpcode, // Store response data
        'created_at'        => now()
    ];

    // Insert log with null job_id
    $logId    = DB::table('bulk_api_request_response_logs')->insertGetId($logData);

    if($httpcode == 200){
        return json_decode($response, true);

    }else{
        return $response['results'] = "Something went Wrong!$httpcode";
    }


}

function getBouncifyCreditBalance()
{
    $apiKey = envparam('bouncify_key');
    $client = new \GuzzleHttp\Client();

    $response = $client->request('GET', "https://api.bouncify.io/v1/info?apikey=$apiKey", [
    'headers' => [
        'accept' => 'application/json',
    ],
    ]);

    if($response->getStatusCode() ==200){
        $return = json_decode($response->getBody(),true);
        return $return['credits_info']['credits_remaining'];
    }else{
        return 0;
    }
}

function getDebounceCreditBalance()
{
    $apiKey = envparam('debounce_key');
    $client = new \GuzzleHttp\Client();

    $response = $client->request('GET', "https://api.debounce.io/v1/balance/?api=$apiKey", [
    'headers' => [
        'accept' => 'application/json',
    ],
    ]);

    if($response->getStatusCode() ==200){
        $return = json_decode($response->getBody(),true);
        return $return['balance'];
    }else{
        return 0;
    }
}

function notifyCreditBalance($credits,$user_name)
{
    if(env('API_PLATFORM') == "bouncify"){
        $avilable_cresits = getBouncifyCreditBalance()-$credits;
        Notification::route('mail', env('ADMIN_EMAIL'))->notify(new ConfirmationCode('Credit Purchase Notification',['ADMIN_NAME'=>env('ADMIN_NAME'),'name'=>$user_name,'credits'=>$credits,'balance_credits'=>$avilable_cresits,'PLATFORM'=> 'Bouncify'],'admin-credits-notification'));

    }elseif(env('API_PLATFORM') == "debouncee"){
        $avilable_cresits = getDebounceCreditBalance()-$credits;
        Notification::route('mail', env('ADMIN_EMAIL'))->notify(new ConfirmationCode('Credit Purchase Notification',['ADMIN_NAME'=>env('ADMIN_NAME'),'name'=>$user_name,'credits'=>$credits,'balance_credits'=>$avilable_cresits,'PLATFORM'=> 'Debounce'],'admin-credits-notification'));
    }
}

function getCurrency($ip = null)
{
    try {
        if (!$ip) {
            $ip = request()->ip();
        }
        $ip = trim($ip);
        $countryCurrencies = [
            'AF' => 'AFN',
            'AL' => 'ALL',
            'DZ' => 'DZD',
            'AS' => 'USD',
            'AD' => 'EUR',
            'AO' => 'AOA',
            'AI' => 'XCD',
            'AQ' => 'USD',
            'AG' => 'XCD',
            'AR' => 'ARS',
            'AM' => 'AMD',
            'AW' => 'AWG',
            'AU' => 'AUD',
            'AT' => 'EUR',
            'AZ' => 'AZN',
            'BS' => 'BSD',
            'BH' => 'BHD',
            'BD' => 'BDT',
            'BB' => 'BBD',
            'BY' => 'BYN',
            'BE' => 'EUR',
            'BZ' => 'BZD',
            'BJ' => 'XOF',
            'BM' => 'BMD',
            'BT' => 'INR',
            'BO' => 'BOB',
            'BA' => 'BAM',
            'BW' => 'BWP',
            'BV' => 'NOK',
            'BR' => 'BRL',
            'IO' => 'USD',
            'BN' => 'BND',
            'BG' => 'BGN',
            'BF' => 'XOF',
            'BI' => 'BIF',
            'KH' => 'KHR',
            'CM' => 'XAF',
            'CA' => 'CAD',
            'CV' => 'CVE',
            'KY' => 'KYD',
            'CF' => 'XAF',
            'TD' => 'XAF',
            'CL' => 'CLP',
            'CN' => 'CNY',
            'CX' => 'AUD',
            'CC' => 'AUD',
            'CO' => 'COP',
            'KM' => 'KMF',
            'CG' => 'XAF',
            'CD' => 'CDF',
            'CK' => 'NZD',
            'CR' => 'CRC',
            'CI' => 'XOF',
            'HR' => 'EUR',
            'CU' => 'CUP',
            'CY' => 'EUR',
            'CZ' => 'CZK',
            'DK' => 'DKK',
            'DJ' => 'DJF',
            'DM' => 'XCD',
            'DO' => 'DOP',
            'EC' => 'USD',
            'EG' => 'EGP',
            'SV' => 'USD',
            'GQ' => 'XAF',
            'ER' => 'ERN',
            'EE' => 'EUR',
            'SZ' => 'SZL',
            'ET' => 'ETB',
            'FK' => 'FKP',
            'FO' => 'DKK',
            'FJ' => 'FJD',
            'FI' => 'EUR',
            'FR' => 'EUR',
            'GF' => 'EUR',
            'PF' => 'XPF',
            'GA' => 'XAF',
            'GM' => 'GMD',
            'GE' => 'GEL',
            'DE' => 'EUR',
            'GH' => 'GHS',
            'GI' => 'GIP',
            'GR' => 'EUR',
            'GL' => 'DKK',
            'GD' => 'XCD',
            'GP' => 'EUR',
            'GU' => 'USD',
            'GT' => 'GTQ',
            'GG' => 'GBP',
            'GN' => 'GNF',
            'GW' => 'XOF',
            'GY' => 'GYD',
            'HT' => 'HTG',
            'HM' => 'AUD',
            'VA' => 'EUR',
            'HN' => 'HNL',
            'HK' => 'HKD',
            'HU' => 'HUF',
            'IS' => 'ISK',
            'IN' => 'INR',
            'ID' => 'IDR',
            'IR' => 'IRR',
            'IQ' => 'IQD',
            'IE' => 'EUR',
            'IM' => 'GBP',
            'IL' => 'ILS',
            'IT' => 'EUR',
            'JM' => 'JMD',
            'JP' => 'JPY',
            'JE' => 'GBP',
            'JO' => 'JOD',
            'KZ' => 'KZT',
            'KE' => 'KES',
            'KI' => 'AUD',
            'KP' => 'KPW',
            'KR' => 'KRW',
            'KW' => 'KWD',
            'KG' => 'KGS',
            'LA' => 'LAK',
            'LV' => 'EUR',
            'LB' => 'LBP',
            'LS' => 'LSL',
            'LR' => 'LRD',
            'LY' => 'LYD',
            'LI' => 'CHF',
            'LT' => 'EUR',
            'LU' => 'EUR',
            'MO' => 'MOP',
            'MG' => 'MGA',
            'MW' => 'MWK',
            'MY' => 'MYR',
            'MV' => 'MVR',
            'ML' => 'XOF',
            'MT' => 'EUR',
            'MH' => 'USD',
            'MQ' => 'EUR',
            'MR' => 'MRU',
            'MU' => 'MUR',
            'YT' => 'EUR',
            'MX' => 'MXN',
            'FM' => 'USD',
            'MD' => 'MDL',
            'MC' => 'EUR',
            'MN' => 'MNT',
            'ME' => 'EUR',
            'MS' => 'XCD',
            'MA' => 'MAD',
            'MZ' => 'MZN',
            'MM' => 'MMK',
            'NA' => 'NAD',
            'NR' => 'AUD',
            'NP' => 'NPR',
            'NL' => 'EUR',
            'NC' => 'XPF',
            'NZ' => 'NZD',
            'NI' => 'NIO',
            'NE' => 'XOF',
            'NG' => 'NGN',
            'NU' => 'NZD',
            'NF' => 'AUD',
            'MK' => 'MKD',
            'MP' => 'USD',
            'NO' => 'NOK',
            'OM' => 'OMR',
            'PK' => 'PKR',
            'PW' => 'USD',
            'PS' => 'ILS',
            'PA' => 'PAB',
            'PG' => 'PGK',
            'PY' => 'PYG',
            'PE' => 'PEN',
            'PH' => 'PHP',
            'PN' => 'NZD',
            'PL' => 'PLN',
            'PT' => 'EUR',
            'PR' => 'USD',
            'QA' => 'QAR',
            'RE' => 'EUR',
            'RO' => 'RON',
            'RU' => 'RUB',
            'RW' => 'RWF',
            'BL' => 'EUR',
            'SH' => 'SHP',
            'KN' => 'XCD',
            'LC' => 'XCD',
            'MF' => 'EUR',
            'PM' => 'EUR',
            'VC' => 'XCD',
            'WS' => 'WST',
            'SM' => 'EUR',
            'ST' => 'STN',
            'SA' => 'SAR',
            'SN' => 'XOF',
            'RS' => 'RSD',
            'SC' => 'SCR',
            'SL' => 'SLL',
            'SG' => 'SGD',
            'SX' => 'ANG',
            'SK' => 'EUR',
            'SI' => 'EUR',
            'SB' => 'SBD',
            'SO' => 'SOS',
            'ZA' => 'ZAR',
            'GS' => 'GBP',
            'SS' => 'SSP',
            'ES' => 'EUR',
            'LK' => 'LKR',
            'SD' => 'SDG',
            'SR' => 'SRD',
            'SJ' => 'NOK',
            'SE' => 'SEK',
            'CH' => 'CHF',
            'SY' => 'SYP',
            'TW' => 'TWD',
            'TJ' => 'TJS',
            'TZ' => 'TZS',
            'TH' => 'THB',
            'TL' => 'USD',
            'TG' => 'XOF',
            'TK' => 'NZD',
            'TO' => 'TOP',
            'TT' => 'TTD',
            'TN' => 'TND',
            'TR' => 'TRY',
            'TM' => 'TMT',
            'TC' => 'USD',
            'TV' => 'AUD',
            'UG' => 'UGX',
            'UA' => 'UAH',
            'AE' => 'AED',
            'GB' => 'GBP',
            'US' => 'USD',
            'UM' => 'USD',
            'UY' => 'UYU',
            'UZ' => 'UZS',
            'VU' => 'VUV',
            'VE' => 'VES',
            'VN' => 'VND',
            'VG' => 'USD',
            'VI' => 'USD',
            'WF' => 'XPF',
            'EH' => 'MAD',
            'YE' => 'YER',
            'ZM' => 'ZMW',
            'ZW' => 'ZWL'
        ];
        
        // Use a free geolocation API to get country/currency
        $response = Http::get("https://ipwho.is/{$ip}");
        if ($response->successful()) {
            $data = $response->json();
            return [
                'country_code' => $data['country_code'],
                'currency' => $countryCurrencies[$data['country_code']] ?? 'USD'
            ];
        }
    } catch (\Exception $e) {
    //     // fallback
    }
    return [
        'country_code' => 'US',
        'currency' =>  'USD'
    ];
}

function getConvertedAmount($amount, $currencyCode)
{
    try {
        if ($currencyCode === 'USD') {
            return $amount;
        }

        // Use ExchangeRate.host (no API key needed)
        $response = Http::get("https://v6.exchangerate-api.com/v6/753737b16cafcc1cde4e814f/latest/USD");
        
        if ($response->successful()) {
            $data = $response->json();
            if(isset($data['conversion_rates'][$currencyCode])){
                $convertedAmount = $amount * $data['conversion_rates'][$currencyCode];
                return [
                    'amount' => round($convertedAmount, 2),
                    'currency' => $currencyCode
                ];
            }
            else{
                return [
                    'amount' => $amount,
                    'currency' => 'USD'
                ];
            }
        }
    } catch (\Exception $e) {
    //     // fallback to USD amount if conversion fails
    }

    return [
        'amount' => $amount,
        'currency' => 'USD'
    ];
}
