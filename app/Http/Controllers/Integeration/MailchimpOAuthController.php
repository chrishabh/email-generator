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

class MailchimpOAuthController extends Controller
{
    public function redirectToMailchimp()
    {
        $MAILCHIMP_CLIENT_ID       = envparam('MAILCHIMP_CLIENT_ID');
        $MAILCHIMP_CLIENT_SECRET   = envparam('MAILCHIMP_CLIENT_SECRET');
        $MAILCHIMP_REDIRECT_URI    = envparam('MAILCHIMP_REDIRECT_URI');

        $query = http_build_query([
            'response_type' => 'code',
            'client_id' =>   $MAILCHIMP_CLIENT_ID  ,
            'redirect_uri' =>  $MAILCHIMP_REDIRECT_URI ,
        ]);
        // pp($query);
        return redirect("https://login.mailchimp.com/oauth2/authorize?$query");
    }

    public function handleCallback(Request $request)
    {
        $code = $request->input('code');

        $client = new Client();

        try{
             
            $tool = IntegrationTool::where('name', 'Mailchimp')
            ->where('slug', 'mailchimp')
            ->first();
             
            if ($tool) {
            $MAILCHIMP_CLIENT_ID       = envparam('MAILCHIMP_CLIENT_ID');
            $MAILCHIMP_CLIENT_SECRET   = envparam('MAILCHIMP_CLIENT_SECRET');
            $MAILCHIMP_REDIRECT_URI    = envparam('MAILCHIMP_REDIRECT_URI');
                $response = $client->post('https://login.mailchimp.com/oauth2/token', [
                    'form_params' => [
                        'grant_type' => 'authorization_code',
                        'client_id' =>  $MAILCHIMP_CLIENT_ID,
                        'client_secret' => $MAILCHIMP_CLIENT_SECRET,
                        'redirect_uri' => $MAILCHIMP_REDIRECT_URI,
                        'code' => $code,
                    ],
                ]); 
                $data = json_decode($response->getBody(), true);
                $accessToken = $data['access_token'];  
    
                // Get metadata
                $metaResponse = $client->get('https://login.mailchimp.com/oauth2/metadata', [
                    'headers' => ['Authorization' => "OAuth $accessToken"]
                ]); 

                $meta = json_decode($metaResponse->getBody(), true);
                $exists = Integration::where('mc_user_id', $meta['user_id'])->exists();
                if ($exists) { 
                    Session::flash('error', 'This Mailchimp account is already connected.');
                    return redirect('/Integration');
                }
                Session::put('mc_token', $accessToken);
                Session::put('mc_dc', $meta['dc']);
                Session::put('mc_user_id', $meta['user_id']);
                Session::put('mc_', $meta); 
                $integration                = new Integration();
                $integration->tool_id       = $tool->id;
                $integration->mc_token      = $accessToken;
                $integration->mc_dc         = $meta['dc'];
                $integration->mc_user_id    = $meta['user_id'];
                $integration->user_id       = Auth::user()->id;
                $integration->status        = 'verified';
                $integration->service_name  = $tool->slug;
                $integration->mc_dc         = $meta['dc'];
                $integration->name          = $meta['accountname'] ?? null;
                $integration->emails        = $meta['login']['email'] ?? null;
                $integration->metadata      = json_encode($meta);
                $success                    =  $integration->save(); 
                Session::flash('success', 'Mailchimp connected successfully!');
            } else { 
                Session::flash('error', 'Mailchimp tool not found.');
            } 
            return redirect('/Integration');

        }catch (\Exception $e) {
        // Flash error message  
            echo $e->getMessage();
            Session::flash('error', 'Failed to connect Mailchimp. Please try again.'); 
            return redirect('/Integration');
        } 
    }



    public function validateEmails(Request $request)
    {
        $userId          = $request->query('userId');
        $token           = $request->query('token'); 
        $mc_dc           = $request->query('mc'); 
        $toolName        = $request->query('toolName'); 
        $integeration_id = $request->query('integeration_id'); 

         if(!$userId || !$token || !$mc_dc || !$toolName || !$integeration_id) {
            return response()->json(
            [
                'message' => 'Unauthorized',
                'success' => false,
                'error' => 'UserId and token are required'
            ], 401);
        }

        // $exists = Integration::where('mc_user_id', $userId)->where('mc_dc',$mc_dc)->where('mc_token',$token)->exists();

        // if(!$exists) {
        //     return response()->json(
        //     [
        //         'message' => 'Unauthorized',
        //         'success' => false,
        //         'error' => 'Invalid userId, token or data center'
        //     ], 401);
        // }

        // $accessToken = Session::get('mc_token');
        // $dc = Session::get('mc_dc');
        // if(!$accessToken){
            $accessToken = $token;
            $dc          = $mc_dc; // Default data center if not set
        // }
        $client = new Client([
            'base_uri' => "https://$dc.api.mailchimp.com/3.0/",
            'headers' => ['Authorization' => "OAuth $accessToken"]
        ]); 
        try {
            DB::beginTransaction(); // Start DB transaction

            // Step 1: Get Lists
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

            DB::commit(); // Commit DB transaction 
            // return redirect('/tools')->with([
            //     'success' => 'Emails validated and saved successfully!',
            //     'uploadId' => $uploadId,
            //     'results' => $results
            // ]);
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
