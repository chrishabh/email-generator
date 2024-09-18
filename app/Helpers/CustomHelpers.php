<?php

use App\Models\User;
use App\Notifications\ConfirmationCode;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;

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

function bulkBouncify($path){
    $apiKey = envparam('bulk_bouncify_key');

    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.bouncify.io/v1/bulk?apikey=$apiKey",
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

    if($httpcode == 200){
        
        $return = json_decode($response, true);
        patchBulkBouncify($return['job_id']);
        return $return;

    }else{
        $response['results'] = 'Something went Wrong!';
    }
// Response example.
// {
//     "job_id": "r374aki32rnatv868nntpxloc7dkilszc3eu",
//     "success": true,
//     "message": "Bulk email verification list has been created"
//   }

}

function patchBulkBouncify($job_id)
{
    $apiKey = envparam('bulk_bouncify_key');

    $curl = curl_init();

    curl_setopt_array($curl, array(

    CURLOPT_URL => "https://api.bouncify.io/v1/bulk/$job_id?apikey=$apiKey",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'PATCH',
    ));

    $response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if($httpcode == 200){
        return json_decode($response, true);

    }else{
        $response['results'] = 'Something went Wrong!';
    }

    // {
    //     "job_id": "r374aki32rnatv868nntpxloc7dkilszc3eu",
    //     "success": true,
    //     "message": "Job verification will be attempted shortly. Call /status endpoint to know the status of the Job"
    // }

}

function bulkJobStatus($job_id)
{
    $apiKey = envparam('bulk_bouncify_key');
    $curl = curl_init();

    curl_setopt_array($curl, array(
    // Replace jobId with your list's jobId and API_KEY with your API Key
    CURLOPT_URL => "https://api.bouncify.io/v1/bulk/$job_id?apikey=$apiKey", 
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

    curl_close($curl);

    if($httpcode == 200){
        return json_decode($response, true);

    }else{
        $response['results'] = 'Something went Wrong!';
    }

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

function bulkDownload($job_id)
{

    $apiKey = envparam('bulk_bouncify_key');
    $curl = curl_init();

    curl_setopt_array($curl, array(
    // Replace API_KEY with your API Key and jobId with your list's jobId, you need to download
    CURLOPT_URL => "https://api.bouncify.io/v1/download?jobId=$job_id&apikey=$apiKey",
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

    curl_close($curl);

    if($httpcode == 200){
        deleteBulkJob($job_id);
        return $response;

    }else{
        $response['results'] = 'Something went Wrong!';
    }

//     "Email", "Verification Result",	"Syntax Error",	"ISP", "Role", "Accept-all", "Disposable", "Trap", "Verified At"
// "info@sample.com", "deliverable", "N", "Y", "N", "N", "N", "2021-09-13T06:49:39.280Z"
// "support@sample.in", "accept-all", "N",	"N", "Y", "Y", "N", "2021-09-13T06:49:39.282Z"

}

function deleteBulkJob($job_id)
{

    $apiKey = envparam('bulk_bouncify_key');
    $curl = curl_init();

    curl_setopt_array($curl, array(
    // Replace jobId with your list's jobId and API_KEY with your API Key,you needs to delete
    CURLOPT_URL => "https://api.bouncify.io/v1/bulk/$job_id?apikey=$apiKey",
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

    curl_close($curl);

    if($httpcode == 200){
        return $response;

    }else{
        $response['results'] = 'Something went Wrong!';
    }
}


function singlebouncify($email){
    $apiKey = envparam('bouncify_key');
    $curl = curl_init();

    curl_setopt_array($curl, array(
    // Replace API_KEY with your API Key
    CURLOPT_URL => "https://api.bouncify.io/v1/verify?apikey=$apiKey&email=$email",
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

    curl_close($curl);

    if($httpcode == 200){
        return json_decode($response, true);

    }else{
        $response['results'] = 'Something went Wrong!';
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