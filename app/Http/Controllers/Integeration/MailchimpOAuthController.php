<?php

namespace App\Http\Controllers\Integeration;

use App\Enums\ToolNameEnum;
use App\Http\Controllers\Controller;
use App\Models\BulkUploadEmailFileData;
use App\Models\Integration;
use App\Models\IntegrationTool;
use App\Models\uploadedAndDownloadFileName;
use Illuminate\Http\Request; 
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MailchimpOAuthController extends Controller
{
    /**
     * Redirects the user to the OAuth provider's authorization URL.
     *
     * @param int $toolId The ID of the integration tool.
     * @param string $toolName The name of the integration tool.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
    */
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

    /**
     * Handles the redirection logic for various OAuth tools, either for initial authorization
     * or for token exchange during callback.
     *
     * @param string $toolName The name of the tool (e.g., 'mailchimp', 'google sheets').
     * @param string $clientId The client ID for the OAuth application.
     * @param string $clientSecret The client secret for the OAuth application.
     * @param string $redirect_uri The redirect URI configured for the OAuth application.
     * @param string $auth_login_url The authorization URL for the OAuth provider.
     * @param string|null $token_url The token exchange URL for the OAuth provider.
     * @param bool $is_handle_callback True if this is being called from the handleCallback method.
     * @param string|null $code The authorization code received in the callback.
     * @return \Illuminate\Http\RedirectResponse|array|null Returns a redirect response, access token data, or null on error.
     */

    private static function redirectionToTools($toolName,$clientId,$clientSecret,$redirect_uri,$auth_login_url,$token_url=null,$is_handle_callback = false,$code=null)
    {
        $queryBuildArray = [];

        if (!$clientId || !$redirect_uri || !$auth_login_url) {
            return;
        }
         
        switch (strtolower($toolName)) {
            case  ToolNameEnum::MAILCHIMP:
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
            case ToolNameEnum::GOOGLESHEETS:
                $queryBuildArray['client_id']     = $clientId;
                $queryBuildArray['redirect_uri']  = $redirect_uri;
                if($is_handle_callback==false){
                    $queryBuildArray['scope']         = 'https://www.googleapis.com/auth/spreadsheets https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';
                    $queryBuildArray['response_type'] = 'code';
                    $queryBuildArray['access_type']   = 'offline';
                    $queryBuildArray['prompt']        = 'consent';
                    $auth_login_url                   = $auth_login_url;
                }else{
                    $queryBuildArray['grant_type']     = 'authorization_code';
                    $queryBuildArray['client_secret']  = $clientSecret;
                    $queryBuildArray['code']           = $code;
                    $client = new Client(); 
                    $accessToken = self::getAccessTokenOftool($client, $token_url, $queryBuildArray,$toolName); 
                    return $accessToken;
                }
            break;
            case ToolNameEnum::HUBSPOT:
                $queryBuildArray['client_id']     = $clientId;
                $queryBuildArray['redirect_uri']  = $redirect_uri;
                if($is_handle_callback==false){
                    $queryBuildArray['scope']         = 'content oauth crm.objects.deals.read crm.objects.contacts.read';
                    $auth_login_url                   = $auth_login_url;
                }else{
                    $queryBuildArray['grant_type']     = 'authorization_code';
                    $queryBuildArray['client_secret']  = $clientSecret;
                    $queryBuildArray['code']           = $code;
                    $client = new Client(); 
                    $accessToken = self::getAccessTokenOftool($client, $token_url, $queryBuildArray,$toolName); 
                    return $accessToken;
                }
            break;
            case ToolNameEnum::DROPBOX:
                $queryBuildArray['client_id']     = $clientId;
                $queryBuildArray['redirect_uri']  = $redirect_uri;
                if($is_handle_callback==false){
                    $queryBuildArray['response_type']     = 'code';
                    $queryBuildArray['client_id']         = $clientId;
                    $queryBuildArray['redirect_uri']      = $redirect_uri;
                    $queryBuildArray['state']             = Str::random(16);  
                    $queryBuildArray['scope']             = 'account_info.read files.content.read files.content.write';
                    $queryBuildArray['token_access_type'] = 'offline';
                }else{
                    $queryBuildArray['grant_type']     = 'authorization_code';
                    $queryBuildArray['client_secret']  = $clientSecret;
                    $queryBuildArray['code']           = $code;
                    $client = new Client();  
                    $accessToken = self::getAccessTokenOftool($client, $token_url, $queryBuildArray,$toolName); 
                    return $accessToken;
                } 
            break;
            default:
                return;
        }
        
        $query = http_build_query($queryBuildArray);
        return redirect("$auth_login_url?$query");  
    }

    private static function getAccessTokenOftool($client, $token_url, $queryBuildArray,$toolname){
        $param      = ['form_params' => $queryBuildArray];
        $response   = $client->post("$token_url", $param);
        $data       = json_decode($response->getBody(), true);

        if ($toolname == ToolNameEnum::HUBSPOT || $toolname == ToolNameEnum::DROPBOX ||  $toolname == ToolNameEnum::GOOGLESHEETS) { 
            return $data;
        } else { 
            return $data['access_token'];
        }
        return $accessToken;

    }

    /**
     * Fetches metadata for a given tool using the provided access token.
     *
     * @param \GuzzleHttp\Client $client Guzzle HTTP client instance.
     * @param string $metadata_url The metadata API URL for the tool.
     * @param string $accessToken The access token for authentication.
     * @param string $toolName The name of the tool.
     * @return array|null The decoded metadata array, or null on failure.
    */
    private static function getMetadataOfTool($client, $metadata_url, $accessToken,$toolName =''){
        
        $header = [];
        if($toolName== ToolNameEnum::HUBSPOT){
            $header = ["Authorization"=> "Bearer $accessToken"];
        }elseif($toolName== ToolNameEnum::MAILCHIMP){
            $header = ["Authorization"=> "OAuth $accessToken"];
        } elseif ($toolName == ToolNameEnum::DROPBOX || $toolName == ToolNameEnum::GOOGLESHEETS) { // Added Dropbox
            $header = ["Authorization" => "Bearer $accessToken"];
        }
        try {
            if($toolName == ToolNameEnum::DROPBOX) { 

                $metaResponse = $client->post($metadata_url, [
                    'headers' => $header,
                    // DO NOT include 'json' or 'body' keys to ensure a truly empty body
                ]);
            } else {
                $metaResponse = $client->get($metadata_url, [
                    'headers' => $header // Use the merged headers for other tools
                ]);
            }
            $meta = json_decode($metaResponse->getBody(), true);
            return $meta;
        } catch (\Exception $e) {
            pp($e->getMessage());
            Log::error("Failed to get metadata for $toolName: " . $e->getMessage());
        }
    }

    /**
     * Handles the OAuth callback from various integration tools.
     * Exchanges the authorization code for an access token and stores integration details.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @param string $toolName The name of the tool (from the route parameter).
     * @return \Illuminate\Http\RedirectResponse
    */
    public function handleCallback(Request $request,$toolName)
    {
        $code     = $request->input('code');
        $toolName = strtolower($toolName); 
        if($toolName=='googlesheets'){
            $toolName = 'google sheets';
        }
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
                $AUTH_LOGIN_URL           = $urls['auth_login_url'];
                $AUTH_TOKEN_URL           = $urls['auth_token_url'];
                $AUTH_METADATA_URL        = $urls['auth_metadata_url']; 
                $BASE_API_URL             = $urls['base_api_url'] ?? null;
                $accessTokenData          = self::redirectionToTools($toolName,$CLIENT_ID,$CLIENT_SECRET,$MAILCHIMP_REDIRECT_URI,$AUTH_LOGIN_URL,$AUTH_TOKEN_URL,true,$code);                 
               
                if (empty($accessTokenData)) {
                    Session::flash('error', "Failed to retrieve access token for $originalToolName. Please try again.");
                    return redirect('/tools');
                }

                $accessToken  = null;
                $refreshToken = null;
                $mc_user_id   = null;
                $mc_dc        = null; // Used for Mailchimp, but keeping variable name for consistency
                $accountName  = null;
                $email        = null;
            
                switch ($toolName) {
                    case ToolNameEnum::MAILCHIMP:
                        $accessToken = $accessTokenData;
                        $meta        = self::getMetadataOfTool($client, $AUTH_METADATA_URL, $accessToken, $toolName);
                        if (!$meta) {
                            Session::flash('error', "Failed to retrieve metadata for $originalToolName.");
                            return redirect('/tools');
                        }
                        $mc_user_id  = $meta['user_id'];
                        $mc_dc       = $meta['dc'];
                        $accountName = $meta['accountname'] ?? null;
                        $email       = $meta['login']['email'] ?? null;
                    break;

                    case ToolNameEnum::HUBSPOT:
                        if (isset($accessTokenData['access_token'])) {
                            $accessToken = $accessTokenData['access_token'];
                            $refreshToken = $accessTokenData['refresh_token'] ?? null;
                        } else {
                            Session::flash('error', "Something went wrong with the access token for $originalToolName.");
                            return redirect('/tools');
                        }
                        $meta = self::getMetadataOfTool($client, $AUTH_METADATA_URL, $accessToken, $toolName);
                        if (!$meta) {
                            Session::flash('error', "Failed to retrieve metadata for $originalToolName.");
                            return redirect('/Integration');
                        }
                        $mc_user_id   = $meta['portalId'];
                        $mc_dc        = NULL;  
                        $accountName  = $meta['hub_domain'] ?? null; 
                        $email        = $meta['user']['email'] ?? null;
                    break;

                    case ToolNameEnum::DROPBOX: // Handle Dropbox callback
                        if (isset($accessTokenData['access_token'])) {
                            $accessToken = $accessTokenData['access_token'];
                            $refreshToken = $accessTokenData['refresh_token'] ?? null; // Dropbox also provides refresh tokens
                        } else {
                            Session::flash('error', "Something went wrong with the access token for $originalToolName.");
                            return redirect('/Integration');
                        }
                        $dropboxClient = new Client(['base_uri' => $BASE_API_URL]);
                        $meta          = self::getMetadataOfTool($dropboxClient, $AUTH_METADATA_URL, $accessToken, $toolName);
                        if (!$meta) {
                            Session::flash('error', "Failed to retrieve metadata for $originalToolName.");
                            return redirect('/Integration');
                        }

                        $mc_user_id   = $meta['account_id']; // Dropbox uses account_id
                        $accountName  = $meta['name']['display_name'] ?? null; // User's display name
                        $email        = $meta['email'] ?? null; // User's email
                        $mc_dc        = null; // Dropbox doesn't have a 'dc' equivalent for this purpose
                    break; 
                    case ToolNameEnum::GOOGLESHEETS:
                        if (isset($accessTokenData['access_token'])) {
                            $accessToken = $accessTokenData['access_token'];
                            $refreshToken = $accessTokenData['refresh_token'] ?? null;
                        } else {
                            Session::flash('error', "Something went wrong with the access token for $originalToolName.");
                            return redirect('/tools');
                        } 
                        // Google User Info API for metadata
                        $googleClient = new Client(); // No base_uri needed if metadata_url is absolute
                        $meta         = self::getMetadataOfTool($googleClient, $AUTH_METADATA_URL, $accessToken, $toolName);
                        if (!$meta) {
                            Session::flash('error', "Failed to retrieve metadata for $originalToolName.");
                            return redirect('/tools');
                        }
                        $mc_user_id  = $meta['id'];
                        $accountName = $meta['name'] ?? null;
                        $email       = $meta['email'] ?? null;
                        $mc_dc       = null;
                    break;
                    default:
                        Session::flash('error', 'Unsupported tool encountered during callback.');
                    return redirect('/tools');
                }

                $userId  = Auth::user()->id;
                $exists  = Integration::where('mc_user_id', $mc_user_id)->where('service_name', $toolName)->where('user_id', $userId)->whereNull('deleted_at')->exists();
                
                if ($exists) { 
                    Session::flash('error', "This $originalToolName account is already connected.");
                    return redirect('/tools');
                } 

                $integration                    = new Integration();
                $integration->tool_id           = $tool->id;
                $integration->mc_token          = $accessToken;
                $integration->mc_refresh_token  = $refreshToken;
                $integration->mc_dc             = $mc_dc;
                $integration->mc_user_id        = $mc_user_id;
                $integration->user_id           = Auth::user()->id;
                $integration->status            = 'verified';
                $integration->service_name      = $tool->slug; 
                $integration->name              = $accountName;
                $integration->emails            = $email;
                $integration->metadata          = json_encode($meta);
                $success                        = $integration->save(); 
                Session::flash('success', "$originalToolName connected successfully!");
            } else {  
                Session::flash('error', "$toolName tool not found.");
            } 
            return redirect('/tools');

        }catch (\Exception $e) { 
            pp( $e->getMessage());
            Session::flash('error',  $e->getMessage()); 
            return redirect('/tools');
        } 
    }

    /**
     * Validates and imports emails from connected integration tools (Mailchimp, HubSpot, Dropbox, Google Sheets).
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateEmails(Request $request)
    {
        $userId          = $request->query('userId');
        // $token           = $request->query('token'); 
        $mc_dc           = $request->query('mc'); 
        $toolName        = $request->query('toolName'); 
        $integeration_id = $request->query('integeration_id'); 

         if(!$userId || !$toolName || !$integeration_id) {
            return response()->json(
            [
                'message' => 'Unauthorized',
                'success' => false,
                'error' => 'UserId  are required'
            ], 401);
        }
        $LoggedInuserId = Auth::user()->id;
        $exists         = Integration::where('mc_user_id', $userId)->where('user_id',$LoggedInuserId)->where('service_name',strtolower($toolName))->exists();
        if(!$exists) {
            return response()->json(
            [
                'message' => 'Unauthorized',
                'success' => false,
                'error' => 'Invalid userId, token or data center'
            ], 401);
        }
        $integration = Integration::with('tool')->find($integeration_id);
        if(empty($integration)) {
            return response()->json(['success'=>false,'message'=>'invalid integeration','error' => 'invalid integeration'], 404);
        }
        if(!empty($integration)){
            $toolData     = $integration->tool; 
            $toolId       = $integration->tool_id; 
            $urlJson      = json_decode($toolData->url,true);
            $token_url    = $urlJson['auth_token_url']; 
            $base_api_url = $urlJson['base_api_url']; 
            $accessToken  = $integration->mc_token;
            $dc           = $mc_dc; // Default data center if not set
            $slug         = strtolower($toolName);
            $header       = [];
        }


        try {
            DB::beginTransaction(); // Start DB transaction
            switch($slug){
                case  ToolNameEnum::MAILCHIMP:
                    $base_api_url = self::replaceMailchimpBaseUrl($base_api_url,$dc);
                    $client = new Client([
                        'base_uri'    => "$base_api_url",
                        'headers'     => self::createClientUrlWithHeadBasedOnTools(ToolNameEnum::MAILCHIMP,$accessToken),
                        'http_errors' => true, 
                    ]); 
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
                case  ToolNameEnum::HUBSPOT:
                    $client = new Client([
                        'base_uri'    => "$base_api_url",
                        'headers'     => self::createClientUrlWithHeadBasedOnTools(ToolNameEnum::HUBSPOT,$accessToken),
                        'http_errors' => false, 
                    ]); 
                    $response   = $client->get('contacts/v1/lists/all/contacts/all');
                    $statusCode = $response->getStatusCode();
                    if ($statusCode ==401) {
                        if(!empty($integration)){
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
                                $header      = ['Authorization' => "Bearer $accessToken"];
                                $client = new Client([
                                    'base_uri'    => "$base_api_url",
                                    'headers'     => self::createClientUrlWithHeadBasedOnTools(ToolNameEnum::HUBSPOT,$accessToken),
                                    'http_errors' => true, 
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
                case ToolNameEnum::DROPBOX: // Added Dropbox for validation/listing (conceptual)
                    $client = new Client([
                        'base_uri' => "$base_api_url", // e.g., 'https://api.dropboxapi.com/2/'
                        // Headers will be applied later in getMetadataOfTool or specific request
                        'http_errors' => false, // Set to true for debugging
                    ]);
                    
                    // Attempt to refresh token if 401
                    // Pass only Authorization header, without Content-Type, for this specific request
                    $dropboxAuthHeader = ["Authorization" => "Bearer $accessToken"];
                    $response          = $client->post('users/get_current_account', ['headers' => $dropboxAuthHeader]);
                    $statusCode        = $response->getStatusCode();
                    if ($statusCode == 401) {
                        if (!empty($integration)) {
                            $urlJson = json_decode($integration->tool->url, true);
                            $queryBuildArray = [
                                'grant_type'    => 'refresh_token',
                                'client_id'     => $integration->tool->client_id,
                                'client_secret' => $integration->tool->client_secret,
                                'redirect_uri'  => $urlJson['redirect_url'],
                                'refresh_token' => $integration->mc_refresh_token
                            ];
                            $accessTokenData = self::getAccessTokenOftool($client, $token_url, $queryBuildArray, $slug);
                            if (!empty($accessTokenData) && isset($accessTokenData['access_token'])) {
                                $integration->mc_token         = $accessTokenData['access_token'];
                                $integration->mc_refresh_token = $accessTokenData['refresh_token'];
                                $integration->save();
                                $accessToken = $accessTokenData['access_token'];
                                // Recreate client for subsequent successful API calls with proper headers
                                $client = new Client([
                                    'base_uri' => "$base_api_url",
                                    'http_errors' => true,
                                ]);
                            } else {
                                return response()->json([
                                    'message' => 'Unauthorized',
                                    'success' => false,
                                    'error' => 'Failed to refresh access token for Dropbox'
                                ], 401);
                            }
                        }else {
                            return response()->json([
                                'message' => 'Unauthorized',
                                'success' => false,
                                'error' => 'Invalid integration ID'
                            ], 401);
                        }
                    }
                    // Re-attempt after potential refresh, or if initially successful
                    // Ensure only Authorization header is sent for this call
                    $response = $client->post('users/get_current_account', ['headers' => $dropboxAuthHeader]);
                    if ($response->getStatusCode() == 200) {
                        $accountInfo = json_decode($response->getBody(), true);
                        $emails[]    = $accountInfo['email']; // Add the connected account's email
                    } else {
                        return response()->json([
                            'message' => 'Failed to retrieve Dropbox account info.',
                            'success' => false,
                            'error' => $response->getBody()->getContents()
                        ], $response->getStatusCode());
                    }
                break;

                default:
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => 'Unsupported tool for email validation.', 'error' => 'Unsupported tool'], 400);
            }
            $results                           = []; 
            $upload                            = new uploadedAndDownloadFileName();
            $upload->fileName                  = $toolName.' Import - ' . now()->format('Ymd_His');
            $upload->list_id                   = $listId;
            $upload->user_id                   = Auth::user()->id;
            $upload->tool_name                 = $toolName;
            $upload->tool_id                   = $toolId;
            $upload->integeration_id           = $integeration_id;
            $upload->mc_user_id                = $userId;
            $upload->mc_dc                     = $mc_dc;
            $upload->mc_token                  = $accessToken;
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

    private static function createClientUrlWithHeadBasedOnTools($toolName,$accessToken){
        $header = [];
        if($toolName==ToolNameEnum::MAILCHIMP){
            $header= [
                'Authorization' => "OAuth $accessToken",
                'Accept' => 'application/json',
            ];
        }
        else if($toolName==ToolNameEnum::HUBSPOT){
            $header= [
                'Authorization' => "Bearer $accessToken",
                'Content-Type'  => 'application/json',

            ];
        }
        return $header;
    }

    private static function replaceMailchimpBaseUrl($baseUrl, $dc) {
        return str_replace('://.', "://$dc.", $baseUrl);
    }
    public function unsubscribeSelectedEmailsFromAllLists(Request $request)
    {
        try{
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
            $integration = Integration::with('tool')->find($integeration_id);
            if(empty($integration)) {
                return response()->json(['success'=>false,'message'=>'invalid integeration','error' => 'invalid integeration'], 404);
            }

            if(!empty($integration)){
                $toolData     = $integration->tool; 
                $urlJson      = json_decode($toolData->url,true);
                $base_api_url = $urlJson['base_api_url']; 
                 
            }
            if (!$accessToken || empty($emailsToUnsubscribe)) {
                return response()->json(['success'=>false,'message'=>'Missing token, data center, or email list','error' => 'Missing token, data center, or email list'], 400);
            } 
            $slug  = strtolower($tool_name);
            switch($slug){
                case ToolNameEnum::MAILCHIMP:
                    $base_api_url = self::replaceMailchimpBaseUrl($base_api_url,$dc);
                    $client  = new Client(['base_uri' => "$base_api_url", 'headers'=> self::createClientUrlWithHeadBasedOnTools($slug,$accessToken)]);
                    $lists   = json_decode($client->get('lists')->getBody(), true)['lists']; 
                    $results = []; 
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
                                return response()->json(['status'=>false,'message'=>$e->getMessage() ?? 'Unknown error','data'=> $email], 500);
                            }
                        }
                    }
                break;
                case ToolNameEnum::HUBSPOT:  
                        // $integration = Integration::with('tool')->find($integeration_id);
                        // if(!empty($integration)){
                        //     $toolData    = $integration->tool; 
                        //     $urlJson     = json_decode($toolData->url,true);
                        //     $token_url   = $urlJson['auth_token_url']; 
                        //     $queryBuildArray = [
                        //         'grant_type'    => 'refresh_token',
                        //         'client_id'     => $toolData->client_id,
                        //         'client_secret' => $toolData->client_secret,
                        //         'redirect_uri'  => $urlJson['redirect_url'],
                        //         'refresh_token' => $integration->mc_refresh_token
                        //     ];
                        //     $client = new Client();

                        //     $accessToken = self::getAccessTokenOftool($client, $token_url,$queryBuildArray, $slug);
                        //     $accessToken = $accessToken['access_token'];
                        // }
                    $client = new Client(['base_uri' => "$base_api_url",'headers'=> self::createClientUrlWithHeadBasedOnTools($slug,$accessToken), 'http_errors' => true ]);
                    $results = [];
                    foreach ($emailsToUnsubscribe as $email) {
                        try { 
                            $response  = $client->put( "/email/public/v1/subscriptions/$email", [
                                'json' => ['subscriptionStatuses' => [],'unsubscribeFromAll'=>true]
                            ]);
                            $statusCode = $response->getStatusCode(); 
                            $results[] = ['email'=>$email,'status'=>'unsubscribed','response'=>$response];
                        } 
                        catch(ClientException $e){
                            $errorBody    = $e->getResponse()->getBody()->getContents(); 
                            $decodedError = json_decode($errorBody, true);
                            return response()->json(['status'=>false,'message'=>$decodedError['message'] ?? 'Unknown error','data'=>  $decodedError],  $e->getResponse()->getStatusCode());
                        }
                        catch (\Exception $e) { 
                            return response()->json(['status'=>false,'message'=>$e->getMessage() ?? 'Unknown error','data'=> $email], 500);
                        }
                    }
                break;
            }  
            $file->unsubscribe_results = json_encode($results);
            $file->save();
            return response()->json(['status'=>true,'message'=>'Verification result for unsbscibe  has been completed','data'=> $results], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Something went wrong',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
        
        
    }   

}
