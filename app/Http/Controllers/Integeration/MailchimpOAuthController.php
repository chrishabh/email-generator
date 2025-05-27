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

class MailchimpOAuthController extends Controller
{
    public function redirectToMailchimp()
    {
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' =>  '341572595287',
            'redirect_uri' => 'http://127.0.0.1:8000/mailchimp/callback',
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
                $response = $client->post('https://login.mailchimp.com/oauth2/token', [
                    'form_params' => [
                        'grant_type' => 'authorization_code',
                        'client_id' =>  '341572595287',
                        'client_secret' => 'ac45c64ef6402e49b1b229772020b1b31036f3c7f332140ede',
                        'redirect_uri' => 'http://127.0.0.1:8000/mailchimp/callback',
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
                    return redirect('/tools');
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

}
