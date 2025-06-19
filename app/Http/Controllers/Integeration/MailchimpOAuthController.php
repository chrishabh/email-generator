<?php

namespace App\Http\Controllers\Integeration;

use App\Http\Controllers\Controller;
use App\Models\BulkUploadEmailFileData;
use App\Models\Integration;
use App\Models\IntegrationTool;
use App\Models\uploadedAndDownloadFileName;
use Illuminate\Http\Request; 
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\In;

class MailchimpOAuthController extends Controller
{
    public function redirectToMailchimp($toolId,$toolName)
    {
        $lowercaseofToolName  = strtolower($toolName);
        $tool                 = IntegrationTool::where('name', $toolName)->where('id',  $toolId)->first();
        if (!$tool) {
            Session::flash('error', "$toolName tool not found.");
            return redirect()->back();
        }

        $MAILCHIMP_CLIENT_SECRET  = $tool['client_secret'];
        $MAILCHIMP_CLIENT_ID      = $tool['client_id'];
        $urls                     = $tool['url'];
        $urls                     = json_decode($urls, true); 
        $MAILCHIMP_REDIRECT_URI   = $urls['redirect_url'];
        $auth_login_url           = $urls['auth_login_url'];
        $data                     = self::redirectionToTools($lowercaseofToolName,$MAILCHIMP_CLIENT_ID,$MAILCHIMP_CLIENT_SECRET,$MAILCHIMP_REDIRECT_URI,$auth_login_url);
        if(empty($data)){
            Session::flash('error', 'Invalid tool configuration.');
            return redirect()->back();
        }
        return $data;
        // switch ($lowercaseofToolName) {
        //     case 'mailchimp':
                 
        //         $MAILCHIMP_CLIENT_ID      = $tool['client_secret'];
        //         $MAILCHIMP_CLIENT_SECRET  = $tool['client_id'];
        //         $urls                     = $tool['url'];
        //         $urls                     = json_decode($urls, true); 
        //         $MAILCHIMP_REDIRECT_URI   = $tool['redirect_url'];
        //         $auth_login_url           = $tool['auth_login_url'];
        //         if(empty(self::redirectionToTools($lowercaseofToolName,$MAILCHIMP_CLIENT_ID,$MAILCHIMP_REDIRECT_URI,$auth_login_url))){

        //         }

        //         break;
        //     case 'hubspot':

        //     default:
        //         Session::flash('error', 'Unsupported tool.');
        //         return redirect()->back();
        // }
    }

    private static function redirectionToTools($toolName,$clientId,$clientSecret,$redirect_uri,$auth_login_url,$token_url=null,$is_handle_callback = false,$code=null)
    {
        $queryBuildArray = [];

        if (!$clientId || !$redirect_uri || !$auth_login_url) {
            return;
        }
         
        switch (strtolower($toolName)) {
            case 'mailchimp':
                if($is_handle_callback==false){ 
                    $queryBuildArray['response_type'] = 'code';
                }else {
                    $queryBuildArray['grant_type']     = 'authorization_code';
                    $queryBuildArray['client_secret']  = $clientSecret;
                    $queryBuildArray['code']           = $code;
                }
                $queryBuildArray['client_id']     = $clientId;
                $queryBuildArray['redirect_uri']  = $redirect_uri;
                $auth_login_url                   = $auth_login_url;
                if($is_handle_callback){    
                    $client = new Client(); 
                    $accessToken  =  self::getAccessTokenOftool($client,$token_url,$queryBuildArray,$toolName);
                    return $accessToken;
                }
                break;
            case 'hubspot':
                $queryBuildArray['client_id']     = $clientId;
                $queryBuildArray['redirect_uri']  = $redirect_uri;
                if($is_handle_callback==false){
                    $queryBuildArray['scope']         = 'oauth crm.objects.deals.read crm.objects.contacts.read';
                    $auth_login_url                   = $auth_login_url;
                }else{
                    $queryBuildArray['grant_type']     = 'authorization_code';
                    $queryBuildArray['client_secret']  = $clientSecret;
                    $queryBuildArray['code']           = $code;
                    $client = new Client(); 
                    $accessToken = self::getAccessTokenOftool($client, $token_url, $queryBuildArray,$toolName); 
                    return $accessToken;
                }
                // $queryBuildArray['response_type'] = 'code'; 
                // $queryBuildArray['state']        = Str::random(16);
                break;
            default:
                return;
        }
        
        $query = http_build_query($queryBuildArray);
        return redirect("$auth_login_url?$query");  
    }

    private static function getAccessTokenOftool($client, $token_url, $queryBuildArray,$toolname){
        if($toolname=='hubspot'){
             $param = [
                'form_params' => $queryBuildArray,
            ];
        }else{
            $param = [
            'form_params' => $queryBuildArray,
             ];
        }
    // pp($param);
        $response = $client->post("$token_url", $param);  
        $data = json_decode($response->getBody(), true);
        if($toolname=='hubspot'){
            $accessToken =  $data;
        }
        else{

            $accessToken = $data['access_token']; 
        }
        return $accessToken;

    }

    private static function getMetadataOfTool($client, $metadata_url, $accessToken,$toolName =''){
        if($toolName=='hubspot'){
            $header = ["Authorization"=> "Bearer $accessToken"];
        }else{
            $header = ["Authorization"=> "OAuth $accessToken"];
        }

        $metaResponse = $client->get($metadata_url, [
            'headers' => $header
        ]); 
        $meta = json_decode($metaResponse->getBody(), true);
        return $meta;
    }
    public function handleCallback(Request $request,$toolName)
    {
        $code     = $request->input('code');
        $toolName = strtolower($toolName); 
        
        $client = new Client(); 
        try{ 
            $tool = IntegrationTool::where('slug', $toolName)->first();
            if (empty($tool)) {
                Session::flash('error', "tool not found.");
               return redirect('/tools');
            } 

            if ($tool) {
                $client                   = new Client(); 
                $CLIENT_SECRET            = $tool['client_secret'];
                $CLIENT_ID                = $tool['client_id'];
                $urls                     = $tool['url'];
                $originalToolName         = $tool['name'];
                $toolName                 = strtolower($tool['name']);
                $urls                     = json_decode($urls, true); 
                $MAILCHIMP_REDIRECT_URI   = $urls['redirect_url']; 
                $auth_login_url           = $urls['auth_login_url'];
                $auth_token_url           = $urls['auth_token_url'];
                $auth_metadata_url        = $urls['auth_metadata_url']; 
                $accessToken              = self::redirectionToTools($toolName,$CLIENT_ID,$CLIENT_SECRET,$MAILCHIMP_REDIRECT_URI,$auth_login_url,$auth_token_url,true,$code);
                $refreshToken             = null;
                if( $toolName == 'hubspot' && isset($accessToken['access_token']) ){
                    $refreshToken = $accessToken['refresh_token']; 
                    $accessToken = $accessToken['access_token'];
                }else if($toolName == 'hubspot' && !isset($accessToken['access_token']) ) {
                    Session::flash('error', "something went wrong with the access token of $originalToolName.");
                    return redirect('/tools'); 
                }
                $meta                     = self::getMetadataOfTool($client,$auth_metadata_url,$accessToken,$toolName);
                if( $toolName == 'hubspot'){
                    $mc_user_id = $meta['portalId'];
                    $mc_dc      = NULL; 
                }else{
                    $mc_user_id = $meta['user_id'];
                    $mc_dc      = $meta['dc']; 
                }
                $userId                   = Auth::user()->id;
                $exists                   = Integration::where('mc_user_id',  $mc_user_id)->where('service_name',"$toolName")->where('user_id',$userId)->whereNull('deleted_at')->exists();
                if ($exists) { 
                    Session::flash('error', "This $originalToolName account is already connected.");
                    return redirect('/tools');
                } 
                $integration                    = new Integration();
                $integration->tool_id           = $tool->id;
                $integration->mc_token          = $accessToken;
                $integration->mc_refresh_token  = $refreshToken;
                $integration->mc_dc         = $mc_dc;
                $integration->mc_user_id    = $mc_user_id;
                $integration->user_id       = Auth::user()->id;
                $integration->status        = 'verified';
                $integration->service_name  = $tool->slug; 
                $integration->name          = $meta['accountname'] ?? null;
                $integration->emails        = $meta['login']['email'] ?? null;
                $integration->metadata      = json_encode($meta);
                $success                    =  $integration->save(); 
                Session::flash('success', "$originalToolName connected successfully!");
            } else {  
                Session::flash('error', "$toolName tool not found.");
            } 
            return redirect('/tools');

        }catch (\Exception $e) {
        // Flash error message  
            echo $e->getMessage();
            Session::flash('error', 'Failed to connect Mailchimp. Please try again.'); 
            return redirect('/tools');
        } 
    }



    public function validateEmails(Request $request)
    {
        $userId          = $request->query('userId');
        $token           = $request->query('token'); 
        $mc_dc           = $request->query('mc'); 
        $toolName        = $request->query('toolName'); 
        $integeration_id = $request->query('integeration_id'); 

         if(!$userId || !$token || !$toolName || !$integeration_id) {
            return response()->json(
            [
                'message' => 'Unauthorized',
                'success' => false,
                'error' => 'UserId and token are required'
            ], 401);
        }
        $LoggedInuserId = Auth::user()->id;
        $exists         = Integration::where('mc_user_id', $userId)->where('user_id',$LoggedInuserId)->where('service_name',strtolower($toolName))->where('mc_token',$token)->exists();
        if(!$exists) {
            return response()->json(
            [
                'message' => 'Unauthorized',
                'success' => false,
                'error' => 'Invalid userId, token or data center'
            ], 401);
        }
 
        $accessToken = $token;
        $dc          = $mc_dc; // Default data center if not set
        $slug        = strtolower($toolName);
        $header      = [];

        if($slug == 'hubspot'){
            $url    = 'https://api.hubapi.com/';
            $header = ['Authorization' => "Bearer $accessToken"];
            $error   = false;
        }else if($slug == 'mailchimp'){
            $url     = "https://$dc.api.mailchimp.com/3.0/";
            $header  =  ['Authorization' => "OAuth $accessToken"];
            $error   = true;
        } 

        $client = new Client([
            'base_uri' => "$url",
            'headers' =>  $header,
            'http_errors' => $error, 
        ]); 
        try {
            DB::beginTransaction(); // Start DB transaction
            switch($slug){
                case 'mailchimp':
                    $lists = json_decode($client->get('lists')->getBody(), true);
                    if (empty($lists['lists'])) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'No lists found',
                            'success' => false,
                            'error' => 'No Mailchimp lists available for this account'
                        ], 404);
                    }
                    
                    $listId = $lists['lists'][0]['id']; 
                    // Step 2: Get members
                    $members = json_decode($client->get("lists/$listId/members")->getBody(), true);
                    $emails = array_column($members['members'], 'email_address'); 
                break;
                case 'hubspot':
                    $response   = $client->get('contacts/v1/lists/all/contacts/all');
                    $statusCode = $response->getStatusCode();
                    if ($statusCode === 401) {
                        $integration = Integration::with('tool')->find($integeration_id);
                        if(!empty($integration)){
                            $toolData    = $integration->tool; 
                            $urlJson     = json_decode($toolData->url,true);
                            $token_url   = $urlJson['auth_token_url']; 
                            $queryBuildArray = [
                                'grant_type'    => 'refresh_token',
                                'client_id'     => $toolData->client_id,
                                'client_secret' => $toolData->client_secret,
                                'redirect_uri'  => $urlJson['redirect_url'],
                                'refresh_token' => $integration->mc_refresh_token
                            ];
                            $accessToken = self::getAccessTokenOftool($client, $token_url,$queryBuildArray, $slug);
                            if(!empty($accessToken)){ 
                                $integration->mc_token         = $accessToken['access_token'];
                                $integration->mc_refresh_token = $accessToken['refresh_token'];
                                $integration->save();
                                $accessToken = $accessToken['access_token'];
                                $header = ['Authorization' => "Bearer $accessToken"];
                                $client = new Client([
                                    'base_uri' => "$url",
                                    'headers' =>  $header,
                                    'http_errors' => $error, 
                                ]);  
                            }else{
                                return response()->json([
                                    'message' => 'Unauthorized',
                                    'success' => false,
                                    'error' => 'Failed to refresh access token'
                                ], 401);
                            }
                        }else{
                            return response()->json([
                                'message' => 'Unauthorized',
                                'success' => false,
                                'error' => 'Invalid integration ID'
                            ], 401);    
                        }
                    }
                    $res     = json_decode($client->get('contacts/v1/lists/all/contacts/all')->getBody(), true);
                    $listId  = $res['contacts'][0]['vid'];
                    $emails  = array_map(fn($c)=>$c['identity-profiles'][0]['identities'][0]['value'], $res['contacts']);
                break;
            }
            $results = []; 
            $upload = new uploadedAndDownloadFileName();
            $upload->fileName                  = $toolName.' Import - ' . now()->format('Ymd_His');
            $upload->list_id                   = $listId;
            $upload->user_id                   = Auth::user()->id;
            $upload->tool_name                 = $toolName;
            $upload->integeration_id           = $integeration_id;
            $upload->mc_user_id                = $userId;
            $upload->mc_dc                     = $mc_dc;
            $upload->mc_token                  = $token;
            $upload->is_tools_integerate_email = '1';
            $upload->uploadedFileLocation      = NULL;
            $upload->downloadFileName          = NULL;
            $upload->downloadFileLocation      = NULL;
            $upload->created_at                = now();
            $upload->updated_at                = now(); 
            $upload->save();
            $uploadId                          = $upload->id;
            foreach ($emails as $email) {
                // Optionally validate via Bouncify here...

                // Step 3: Save each email
                $record = new BulkUploadEmailFileData();
                $record->email                     = $email;
                $record->file_id                   = $uploadId;
                $record->importedBy                = Auth::user()->id;
                $record->is_tools_integerate_email = '1';
                $record->type                      = 'bulk';
                $record->created_at                = now();
                $record->updated_at                = now(); 
                $record->save();
                $results[] = ['email' => $email];
            }

            DB::commit();
             return response()->json([
                'message' => 'Emails validated and saved successfully!',
                'success' => true,
                'results' => $results
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); // Roll back DB transaction on error
            return response()->json([
                'message' => 'Something went wrong',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function unsubscribeSelectedEmailsFromAllLists(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emails' => 'required|array|min:1',
            'emails.*' => 'string',
            'fileId' => 'nullable|integer' // Optional if you're tracking fileId
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'error' => $validator->errors()
            ], 422);
        }

        $emailsToUnsubscribe = $request->input('emails');
        $fileId = $request->input('fileId');
        $emailsToUnsubscribeNew = [];
        $file = uploadedAndDownloadFileName::find($fileId);
        if(!$file){
            return response()->json(['status'=>false,'message'=> 'data is not found.','error' => 'File not found'], 404);
        }

        $builkUploadEmailFileData  = $file->bulkUploadEmailFileData;
        if(($builkUploadEmailFileData->isNotEmpty())){
            $filteredEmails   = $builkUploadEmailFileData->whereIn('apiStatus',$emailsToUnsubscribe);
            if($filteredEmails->isNotEmpty()){
                $emailsToUnsubscribeNew = $filteredEmails->pluck('email')->toArray();
            }  
        }

        if(empty($emailsToUnsubscribeNew)){
            return response()->json(['status'=>false,'message'=> 'No emails to unsubscribe.','error' => 'No emails to unsubscribe'], 404);
        }

        $mcUserId            = $file->mc_user_id;
        $accessToken         = $file->mc_token;
        $tool_name           = $file->tool_name;
        $list_id             = $file->list_id;
        $integeration_id     = $file->integeration_id;
        $dc                  = $file->mc_dc;  
        $emailsToUnsubscribe = $emailsToUnsubscribeNew;  

        if (!$accessToken || !$dc || empty($emailsToUnsubscribe)) {
            return response()->json(['success'=>false,'message'=>'Missing token, data center, or email list','error' => 'Missing token, data center, or email list'], 400);
        }

        $client = new Client([
            'base_uri' => "https://$dc.api.mailchimp.com/3.0/",
            'headers' => [
                'Authorization' => "OAuth $accessToken",
                'Accept' => 'application/json',
            ]
        ]);

        // Step 1: Get all lists
        $listsResponse = $client->get('lists');
        $lists = json_decode($listsResponse->getBody(), true)['lists'];

        $results = []; 
        // Step 2: Loop through each list and unsubscribe emails
        foreach ($lists as $list) {
            $listId = $list['id'];

            foreach ($emailsToUnsubscribe as $email) {
                $subscriberHash = md5(strtolower($email));

                try {
                    $client->patch("lists/$listId/members/$subscriberHash", [
                        'json' => [
                            'status' => 'unsubscribed'
                        ]
                    ]);
                    $results[] = ['email' => $email, 'list' => $list['name'], 'status' => 'unsubscribed'];
                } catch (\Exception $e) {
                    $results[] = ['email' => $email, 'list' => $list['name'], 'status' => 'error', 'message' => $e->getMessage()];
                }
            }
        }
        // Step 3: Save results to the file
        $file->unsubscribe_results = json_encode($results);
        $file->save();
        return response()->json(['status'=>true,'message'=>'Verification result for unsbscibe  has been completed','data'=> $results], 200);
    }   

}
