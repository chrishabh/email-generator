<?php

namespace App\Http\Controllers\Integeration;

use App\Enums\ToolNameEnum;
use App\Http\Controllers\Controller;
use App\Models\BulkUploadEmailFileData;
use App\Models\Integration;
use App\Models\IntegrationTool;
use App\Models\ToolSelectedFile;
use App\Models\uploadedAndDownloadFileName;
use Illuminate\Http\Request; 
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
                    $queryBuildArray['scope']             = 'account_info.read files.content.read files.content.write files.metadata.read';
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
            case ToolNameEnum::ZOHO:
                $queryBuildArray['client_id']     = $clientId;
                $queryBuildArray['redirect_uri']  = $redirect_uri;
                if($is_handle_callback==false){
                    $queryBuildArray['scope']         = 'AaaServer.profile.Read,ZohoCRM.modules.ALL';
                    $queryBuildArray['response_type'] = 'code';
                    $queryBuildArray['access_type']   = 'offline';
                    $queryBuildArray['prompt']        = 'consent';
                    $auth_login_url                   = $auth_login_url;
                }else{
                    $queryBuildArray['client_secret']  = $clientSecret;
                    $queryBuildArray['code']           = $code;
                    $client = new Client(); 
                    $accessToken = self::getAccessTokenOftool($client, $token_url, $queryBuildArray,$toolName); 
                    return $accessToken;
                }
            break;
            case ToolNameEnum::CAMPAIGNMONITOR:
                $queryBuildArray['client_id']    = $clientId;
                $queryBuildArray['redirect_uri'] = $redirect_uri;
                if ($is_handle_callback == false) {
                    $queryBuildArray['scope']         = 'ManageLists,ImportSubscribers,ViewSubscribersInReports';
                    $queryBuildArray['response_type'] = 'code';
                    $auth_login_url                   = $auth_login_url;
                } else {
                    $queryBuildArray['grant_type']     = 'authorization_code';
                    $queryBuildArray['client_secret']  = $clientSecret;
                    $queryBuildArray['code']           = $code;
                    $client                            = new Client();
                    $accessToken                       = self::getAccessTokenOftool($client, $token_url, $queryBuildArray, $toolName);
                    return $accessToken;
                }
            break;
            case ToolNameEnum::AWEBER:
                $queryBuildArray['client_id']     = $clientId;
                $queryBuildArray['redirect_uri']  = $redirect_uri;
                if($is_handle_callback == false){
                    $queryBuildArray['response_type'] = 'code';
                    $queryBuildArray['scope']         = 'account.read list.read subscriber.read subscriber.write'; // Use scope from DB
                    $auth_login_url                   = $auth_login_url;
                } else {
                    $queryBuildArray['grant_type']     = 'authorization_code';
                    $queryBuildArray['client_secret']  = $clientSecret;
                    $queryBuildArray['code']           = $code;
                    $client                            = new Client();
                    $accessToken                       = self::getAccessTokenOftool($client, $token_url, $queryBuildArray, $toolName);
                    return $accessToken;
                }
            break;
            default:
                return;
        }
        
        $query = http_build_query($queryBuildArray);
        pp("$auth_login_url?$query");
        return redirect("$auth_login_url?$query");  
    }

    private static function getAccessTokenOftool($client, $token_url, $queryBuildArray,$toolname){
        $param      = ['form_params' => $queryBuildArray];
        $response   = $client->post("$token_url", $param);
        $data       = json_decode($response->getBody(), true);

        if ($toolname == ToolNameEnum::HUBSPOT || $toolname == ToolNameEnum::DROPBOX ||  $toolname == ToolNameEnum::GOOGLESHEETS || $toolname == ToolNameEnum::CAMPAIGNMONITOR) { 
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
        else if($toolName=='campaignmonitor'){
            $toolName = 'campaign monitor';
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
                    case ToolNameEnum::CAMPAIGNMONITOR:
                        if (isset($accessTokenData['access_token'])) {
                            $accessToken  = $accessTokenData['access_token'];
                            $refreshToken = $accessTokenData['refresh_token'] ?? null;
                        } else {
                            Session::flash('error', "Something went wrong with the access token for $originalToolName.");
                            return redirect('/tools');
                        }
                        // For Campaign Monitor, you typically get client details via the API after authentication.
                        // The base_api_url can be used to query user details or client details directly.
                        // Campaign Monitor doesn't have a single /metadata endpoint like some others.
                        // You'd typically query for the clients the user has access to.
                        // For simplicity, we'll try to get the primary client details or user info if available.
                        // If no specific metadata URL, you might fetch initial client data.
                        try {
                            $cmClient = new Client([
                                'base_uri' => $BASE_API_URL,
                                'headers' => ['Authorization' => "Bearer $accessToken"],
                            ]);

                            // Get authenticated user details to find their client ID(s)
                            // API endpoint to get user details is often /api/v3.3/clients.json or similar
                            $clientsResponse = $cmClient->get('clients.json'); // Get a list of clients
                            $clients         = json_decode($clientsResponse->getBody(), true);
                            if (empty($clients)) {
                                Session::flash('error', "No Campaign Monitor clients found for this account.");
                                return redirect('/tools');
                            }

                            // For simplicity, let's assume we integrate with the first client found or prompt user
                            // In a real application, you might let the user choose which client to integrate with.
                            $primaryClient = $clients[0]; 
                            $mc_user_id    = $primaryClient['ClientID']; // Campaign Monitor uses ClientID
                            $accountName   = $primaryClient['Name'];
                            // Campaign Monitor's direct user email might not be available here, 
                            // but you can fetch it if there's a user endpoint or link it to the authenticated user.
                            $email         = null; // Or fetch it from a specific user endpoint if available
                            $mc_dc         = null; // Not directly applicable in the same way as Mailchimp
                            $meta          = $primaryClient; // Store the client details as metadata
                        } catch (\Exception $e) {
                            Log::error("Failed to retrieve Campaign Monitor client data: " . $e->getMessage());
                            Session::flash('error', "Failed to retrieve Campaign Monitor client data. " . $e->getMessage());
                            return redirect('/tools');
                        }
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
                case ToolNameEnum::CAMPAIGNMONITOR:
                    $client = new Client([
                        'base_uri'    => $base_api_url,
                        'headers'     => ['Authorization' => "Bearer $accessToken"],
                        'http_errors' => false, // Handle errors manually to check for 401
                    ]);

                    // Try to get lists
                    $response = $client->get('clients/' . $integration->mc_user_id . '/lists.json');
                    $statusCode = $response->getStatusCode();

                    if ($statusCode === 401) {
                        // Attempt token refresh
                        if (!empty($integration->mc_refresh_token)) {
                            $queryBuildArray = [
                                'grant_type'    => 'refresh_token',
                                'client_id'     => $toolData->client_id,
                                'client_secret' => $toolData->client_secret,
                                'refresh_token' => $integration->mc_refresh_token,
                            ];
                            $accessTokenData = self::getAccessTokenOftool($client, $token_url, $queryBuildArray, $slug);

                            if (!empty($accessTokenData) && isset($accessTokenData['access_token'])) {
                                $integration->mc_token         = $accessTokenData['access_token'];
                                $integration->mc_refresh_token = $accessTokenData['refresh_token'] ?? $integration->mc_refresh_token; // Update refresh token if new one is provided
                                $integration->save();
                                $accessToken = $accessTokenData['access_token'];

                                // Retry with new token
                                $client = new Client([
                                    'base_uri'    => $base_api_url,
                                    'headers'     => ['Authorization' => "Bearer $accessToken"],
                                    'http_errors' => true,
                                ]);
                                $response = $client->get('clients/' . $integration->mc_user_id . '/lists.json');
                                $statusCode = $response->getStatusCode();
                            } else {
                                DB::rollBack();
                                return response()->json([
                                    'message' => 'Unauthorized',
                                    'success' => false,
                                    'error' => 'Failed to refresh access token for Campaign Monitor.'
                                ], 401);
                            }
                        } else {
                            DB::rollBack();
                            return response()->json([
                                'message' => 'Unauthorized',
                                'success' => false,
                                'error' => 'No refresh token available for Campaign Monitor. Please re-authenticate.'
                            ], 401);
                        }
                    }

                    if ($statusCode !== 200) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Failed to retrieve Campaign Monitor lists.',
                            'success' => false,
                            'error' => $response->getBody()->getContents()
                        ], $statusCode);
                    }

                    $lists = json_decode($response->getBody(), true);
                    if (empty($lists)) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'No lists found for this Campaign Monitor account.',
                            'success' => false,
                            'error' => 'No Campaign Monitor lists available'
                        ], 404);
                    }

                    // For simplicity, get subscribers from the first list.
                    // In a production app, you might offer a selection of lists.
                    $listId              = $lists[0]['ListID'];
                    $subscribersResponse = $client->get('lists/' . $listId . '/active.json?pagesize=1000'); // Fetch active subscribers
                    $subscribers         = json_decode($subscribersResponse->getBody(), true);

                    if (empty($subscribers['Results'])) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'No subscribers found in the selected Campaign Monitor list.',
                            'success' => false,
                            'error' => 'No subscribers available in the list'
                        ], 404);
                    }
                    $emails = array_column($subscribers['Results'], 'EmailAddress');
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

    public function getDropboxExcelFiles(Request $request)
    {
        $userId           = $request->query('userId');
        $integeration_id  = $request->query('toolId');
        $toolName         = $request->query('toolName');

        if (!$userId || !$integeration_id) {
            return response()->json([
                'message' => 'Missing parameters',
                'success' => false,
                'error' => 'userId and integeration_id are required.'
            ], 400);
        }

        $integration = Integration::with('tool')->where('id',$integeration_id)->where('mc_user_id',"$userId")->where('user_id',Auth::user()->id)->where('service_name',strtolower($toolName))->find($integeration_id);
        if (empty($integration)) {
            return response()->json([
                'message' => 'Invalid integration or not a Dropbox integration.',
                'success' => false
            ], 404);
        }

        $toolData    = $integration->tool;
        $urlJson     = json_decode($toolData->url, true);
        $base_api_url = $urlJson['base_api_url']; // This is 'https://api.dropboxapi.com/2/'

        try {
            $response = $this->handleTokenRefreshAndRetry(
                $integration,
                $toolData,
                $urlJson,
                function ($currentAccessToken) use ($base_api_url) {
                    $dropboxClient = new Client([
                        'base_uri' => $base_api_url,
                        'headers' => [
                            'Authorization' => "Bearer $currentAccessToken",
                            'Content-Type' => 'application/json',
                        ],
                    ]);
                    return $dropboxClient->post('files/list_folder', [
                        'json' => [
                            'path' => '',
                            'recursive' => true,
                            'limit' => 2000,
                            'include_media_info' => false,
                            'include_deleted' => false,
                            'include_has_explicit_shared_members' => false,
                        ]
                    ]);
                }
            );

            $files = json_decode($response->getBody(), true);
            $excelFiles = [];

            if (isset($files['entries']) && is_array($files['entries'])) {
                foreach ($files['entries'] as $entry) {
                    if ($entry['.tag'] === 'file') {
                        $pathInfo = pathinfo($entry['name']);
                        $extension = strtolower($pathInfo['extension'] ?? '');

                        if (in_array($extension, ['xls', 'xlsx'])) {
                            $excelFiles[] = [
                                'id' => $entry['id'],
                                'name' => $entry['name'],
                                'path_display' => $entry['path_display'],
                                'size' => $entry['size'],
                            ];
                        }
                    }
                }
            }

            return response()->json([
                'message' => 'Dropbox Excel files retrieved successfully.',
                'success' => true,
                'data' => $excelFiles
            ], 200);

        } catch (\Exception $e) {
            Log::error("Error in getDropboxExcelFiles: " . $e->getMessage() . " Stack: " . $e->getTraceAsString());
            return response()->json([
                'message' => 'An error occurred while fetching Dropbox Excel files.',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
 
    private function handleTokenRefreshAndRetry(Integration $integration, $toolData, array $urlJson, callable $originalApiCall)
    {
        $accessToken  = $integration->mc_token;
        $refreshToken = $integration->mc_refresh_token;
        $toolSlug     = strtolower($integration->service_name);
        $token_url    = $urlJson['auth_token_url'];
        $redirect_url = $urlJson['redirect_url']; 
        try {
            // Attempt the original API call with the current access token
            return $originalApiCall($accessToken);
        } catch (ClientException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : null;
            if ($statusCode === 401) {
                Log::warning("Token expired/unauthorized for {$toolSlug} (Integration ID: {$integration->id}). Attempting refresh.");
                if ($refreshToken) {
                    try {
                        $client = new Client();
                        $queryBuildArray = [
                            'grant_type'    => 'refresh_token',
                            'client_id'     => $toolData->client_id,
                            'client_secret' => $toolData->client_secret,
                            'refresh_token' => $refreshToken,
                            // 'redirect_uri'  => $redirect_url, // Ensure redirect_uri is passed for refresh token flow
                        ];
                        $newAccessTokenData = self::getAccessTokenOftool($client, $token_url, $queryBuildArray, $toolSlug);
                        if (!empty($newAccessTokenData) && isset($newAccessTokenData['access_token'])) {
                            $integration->mc_token = $newAccessTokenData['access_token'];
                            // Update refresh token only if a new one is provided (optional for some providers)
                            if (isset($newAccessTokenData['refresh_token'])) {
                                $integration->mc_refresh_token = $newAccessTokenData['refresh_token'];
                            }
                            // $integration->status = 'verified'; // Mark as verified again after successful refresh
                            $integration->save();
                            $newAccessToken = $newAccessTokenData['access_token'];

                            Log::info("Token refreshed successfully for {$toolSlug} (Integration ID: {$integration->id}). Retrying API call.");
                            // Retry the original API call with the new token
                            return $originalApiCall($newAccessToken);
                        } else {
                            // Refresh failed, even if refresh token existed (e.g., refresh token revoked)
                            // $integration->status = 'reauth_required';
                            // $integration->save();
                            Log::error("Failed to get new access token during refresh for {$toolSlug} (Integration ID: {$integration->id}).");
                            throw new \Exception('Failed to refresh access token. Please re-authenticate.');
                        }
                    } catch (\Exception $refreshE) {
                        // Catch exceptions during the refresh process itself
                        // $integration->status = 'reauth_required';
                        // $integration->save();
                        // pp( $refreshE->getMessage());
                        Log::error("Exception during token refresh for {$toolSlug} (Integration ID: {$integration->id}): " . $refreshE->getMessage());
                        throw new \Exception('Failed to refresh access token: ' . $refreshE->getMessage() . '. Please re-authenticate.');
                    }
                } else {
                    // No refresh token available
                    // $integration->status = 'reauth_required';
                    // $integration->save();
                    Log::warning("No refresh token available for {$toolSlug} (Integration ID: {$integration->id}). Re-authentication required.");
                    throw new \Exception('Refresh token not available. Please re-authenticate.');
                }
            }
            // Re-throw if it's a ClientException but not a 401, or if 401 was handled by refresh but original call still failed
            throw $e;
        }
    }

    public function importDropboxExcelFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'integeration_id' => 'required',
            'fileName'       => 'required|string', // The full path of the file to download, e.g., "/folder/my_emails.xlsx"
            'filePath'       => 'required|string', // The original name of the file
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'success' => false,
                'error' => $validator->errors()
            ], 422);
        }

        $userId             = Auth::user()->id;
        $integeration_id    = $request->input('integeration_id');
        $fileName           = $request->input('fileName');
        $integerationUserId = $request->input('userId');
        $filePath           = $request->input('filePath');
        $fileId             = $request->input('fileId');

        $integration = Integration::with('tool')->where('id',$integeration_id)->where('mc_user_id',"$integerationUserId")->where('user_id',$userId)->find($integeration_id);
        if (empty($integration) || strtolower($integration->service_name) !== ToolNameEnum::DROPBOX) {
            return response()->json([
                'message' => 'Invalid integration or not a Dropbox integration.',
                'error' => $validator->errors(),
                'success' => false
            ], 404);
        }

        $toolData           = $integration->tool; 
        $urlJson            = json_decode($toolData->url, true);
        $base_api_url       = $urlJson['base_api_url']; // This is 'https://api.dropboxapi.com/2/' 
        $tempFilePath = null; // Initialize to null for cleanup in finally block

        try {
            DB::beginTransaction();

            // Use the new generalized token refresh and retry logic for downloading the file
            $response = $this->handleTokenRefreshAndRetry(
                $integration,
                $toolData,
                $urlJson,
                function ($currentAccessToken) use ($base_api_url,$filePath) {
                    $contentClient = new Client([
                        'base_uri' => 'https://content.dropboxapi.com/2/', // Dropbox content API base URL
                        'headers' => [
                            'Authorization' => "Bearer $currentAccessToken",
                            'Dropbox-API-Arg' => json_encode(['path' => $filePath]),
                            'Content-Type' => 'text/plain',
                        ],
                        'stream' => true,
                    ]);
                    return $contentClient->post('files/download', ['body' => '']);
                }
            );

            $statusCode = $response->getStatusCode(); 
            if ($statusCode !== 200) {
                DB::rollBack();
                $responseBody = $response->getBody()->getContents();
                Log::error("Dropbox file download failed after refresh attempt ($statusCode): " . $responseBody);
                if ($integration) {
                    $integration->status = 'error';
                    $integration->save();
                }
                return response()->json([
                    'message' => 'Failed to download file from Dropbox after refresh attempt.',
                    'success' => false,
                    'error' => $responseBody
                ], $statusCode);
            }

            // Save the downloaded file temporarily
            $tempFilePath = tempnam(sys_get_temp_dir(), 'dropbox_excel_') . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            file_put_contents($tempFilePath, $response->getBody()->getContents());
 
            $emails            = [];
            $importedCount     = 0;
            $skippedDuplicates = 0;

            // Step 2: Parse the Excel file using PhpSpreadsheet
            try {
                $readerType  = IOFactory::identify($tempFilePath);
                $reader      = IOFactory::createReader($readerType);
                $spreadsheet = $reader->load($tempFilePath);
                $sheet       = $spreadsheet->getActiveSheet();
                $highestRow  = $sheet->getHighestRow();

                for ($row = 1; $row <= $highestRow; $row++) {
                    $email = trim($sheet->getCell('A' . $row)->getValue()); // Trim whitespace
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        // Check for duplicates in the current batch and existing in DB
                        if (!isset($emails[$email]) && !isset($existingEmails[$email])) {
                            $emails[$email] = $email; // Use email as key to prevent duplicates within current batch
                        } else {
                            $skippedDuplicates++;
                            Log::info("Skipped duplicate email: {$email} during Dropbox import.");
                        }
                    }
                }
                $emails = array_values($emails);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error parsing Excel file from Dropbox: " . $e->getMessage());
                // if ($integration) {
                //     $integration->status = 'error';
                //     $integration->save();
                // }
                return response()->json([
                    'message' => 'Failed to parse Excel file. Ensure it is a valid Excel format and emails are in the first column.',
                    'success' => false,
                    'error' => $e->getMessage()
                ], 500);
            }

            if (empty($emails)) {
                DB::rollBack();
                return response()->json([
                    'message' => 'No valid emails found in the selected Excel file.',
                    'success' => false,
                    'error' => 'The selected Excel file does not contain any valid emails in the first column.'
                ], 404);
            }

            // Step 3: Save emails to database
            $upload                            = new uploadedAndDownloadFileName();
            $upload->fileName                  = $fileName;
            $upload->list_id                   = $fileId.'__'.$filePath;
            $upload->user_id                   = $userId;
            $upload->tool_name                 = $toolData->name;
            $upload->tool_id                   = $toolData->id;
            $upload->integeration_id           = $integeration_id;
            $upload->mc_user_id                = $integration->mc_user_id;
            $upload->mc_dc                     = null;
            $upload->mc_token                  = $integration->mc_token; // Ensure this is the latest token after potential refresh
            $upload->is_tools_integerate_email = '1';
            $upload->downloadFileName          = NULL;
            $upload->downloadFileLocation      = NULL;
            $upload->created_at                = now();
            $upload->updated_at                = now();
            $upload->save(); // Save to get the $uploadId

            $uploadId = $upload->id;

            // Define the target directory path within the public disk
            $targetDirectory = strtolower($toolData->name) . '/' . now()->format('Y-m-d') . '/' . $uploadId;
            $finalFilePath   = $targetDirectory . '/' . $fileName;

            // Move the file from temporary location to its final permanent storage location
            try {
                // Ensure the directory exists
                Storage::disk('public')->makeDirectory($targetDirectory);
                // Move the file
                Storage::disk('public')->putFileAs($targetDirectory, new \Illuminate\Http\File($tempFilePath), $fileName);     
                $toolSelected                            = new ToolSelectedFile();
                $toolSelected->file_id                   = $fileId;
                $toolSelected->file_name                 = $fileName;
                $toolSelected->file_path                 = $filePath;
                $toolSelected->user_id                   = $userId; 
                $toolSelected->tool_id                   = $toolData->id;
                $toolSelected->upload_file_path          = $finalFilePath;
                $toolSelected->save();  
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to move imported Dropbox Excel file to permanent storage: " . $e->getMessage());
                return response()->json([
                    'message' => 'Failed to store the imported Excel file permanently.',
                    'success' => false,
                    'error' => $e->getMessage()
                ], 500);
            }

            $results = [];
            foreach ($emails as $email) {
                $record                            = new BulkUploadEmailFileData();
                $record->email                     = $email;
                $record->file_id                   = $uploadId;
                $record->importedBy                = $userId;
                $record->is_tools_integerate_email = '1';
                $record->type                      = 'bulk';
                $record->created_at                = now();
                $record->updated_at                = now();
                $record->save();
                $results[]                         = ['email' => $email];
                $importedCount++;
            }
           
            DB::commit();
            return response()->json([
                'message' => 'Emails imported successfully from Dropbox Excel file!',
                'success' => true,
                'imported_count' => count($emails),
                'results' => $results
            ], 201);

        } catch (\Exception $e) { 
            $responseBody = $e->getResponse()->getBody()->getContents();
            // pp($responseBody);
            DB::rollBack();
            Log::error("General error importing Dropbox Excel emails: " . $e->getMessage() . " Stack: " . $e->getTraceAsString());
            // if ($integration) {
            //     // $integration->status = 'error';
            //     // $integration->save();
            // }
            return response()->json([
                'message' => 'An unexpected error occurred during Dropbox Excel import.',
                'success' => false,
                'error' => $e->getMessage().' '. $responseBody
            ], 500);
        } finally {
            // Ensure the temporary file is always deleted
            if ($tempFilePath && file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
        }
    }


    public function toolConnectionBasedOnApiKey(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_key'   => 'required|string|max:255',
            'tool_id'   => 'required|integer',
            'tool_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()->first(),'message'=>'validation error'], 400);
        }

        $apiKey   = $request->input('api_key');
        $toolId   = $request->input('tool_id');
        $toolName = strtolower($request->input('tool_name'));
        $userId   = Auth::id();

        switch($toolName) {
            case ToolNameEnum::MOOSEND:
                break;
            default:
                return response()->json(['success' => false, 'error' => 'Unsupported tool for API key connection.'], 400);
        }

        if ($toolName !== ToolNameEnum::MOOSEND) {
            return response()->json(['success' => false, 'error' => 'Invalid tool specified for API key connection.'], 400);
        }

        try {
            DB::beginTransaction();

            // 1. Verify the API Key with Moosend by fetching account details
            $client = new Client();
            $moosendBaseApiUrl = 'https://api.moosend.com/v3/';

            $testResponse = $client->get($moosendBaseApiUrl . 'lists.json', [ // Changed from accounts.json to lists.json
                'query' => ['apikey' => $apiKey]
            ]);

            $responseData = json_decode($testResponse->getBody()->getContents(), true);
            if ($testResponse->getStatusCode() === 200 && isset($responseData['Code']) && $responseData['Code'] === 0) {
                // API Key is valid
                $accountInfo      = $responseData['Context']['Account'] ?? [];
                $moosendAccountId = $accountInfo['ID'] ?? null;
                $accountName      = $accountInfo['Name'] ?? 'Moosend Account';
                $accountEmail     = $accountInfo['Email'] ?? null;
                if (!$moosendAccountId) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'error' => 'Could not retrieve Moosend Account ID with the provided API key.'], 400);
                }

                // 2. Store or update the integration
                $moosendTool = IntegrationTool::find($toolId);

                if (!$moosendTool || $moosendTool->slug !== ToolNameEnum::MOOSEND) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'error' => 'Moosend tool not found or mismatched.'], 404);
                }

                $existingIntegration = Integration::where('user_id', $userId)
                                                ->where('tool_id', $toolId)
                                                ->where('service_name', ToolNameEnum::MOOSEND)
                                                ->first();

                if ($existingIntegration) {
                    $existingIntegration->mc_token = $apiKey; // Store the API key in mc_token
                    $existingIntegration->mc_user_id = $moosendAccountId; // Store Moosend Account ID
                    $existingIntegration->status = 'verified';
                    $existingIntegration->name = $accountName;
                    $existingIntegration->emails = $accountEmail;
                    $existingIntegration->metadata = json_encode($accountInfo);
                    $existingIntegration->save();
                } else {
                    $integration = new Integration();
                    $integration->user_id = $userId;
                    $integration->tool_id = $toolId;
                    $integration->service_name = ToolNameEnum::MOOSEND;
                    $integration->mc_token = $apiKey; // Store the API key here
                    $integration->mc_user_id = $moosendAccountId; // Store Moosend Account ID
                    $integration->status = 'verified';
                    $integration->name = $accountName;
                    $integration->emails = $accountEmail;
                    $integration->metadata = json_encode($accountInfo);
                    $integration->save();
                }

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Moosend connected successfully!']);

            } else {
                $errorMessage = $responseData['Error']['Message'] ?? 'Invalid API Key or unable to connect.';
                DB::rollBack();
                return response()->json(['success' => false, 'error' => $errorMessage], 400);
            }

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            DB::rollBack();
            $responseBody = $e->getResponse()->getBody()->getContents();
            Log::error("Moosend API Key verification Guzzle Client error: " . $e->getMessage() . " Response: " . $responseBody);
            return response()->json(['success' => false, 'error' => 'Moosend API Key verification failed: ' . (json_decode($responseBody)->Error->Message ?? $e->getMessage())], $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack();
            pp($e->getMessage());
            Log::error("Error connecting Moosend: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'An unexpected error occurred while connecting Moosend.'], 500);
        }
    }
}
