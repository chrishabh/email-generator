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
                    $queryBuildArray['scope']         = 'https://www.googleapis.com/auth/spreadsheets https://www.googleapis.com/auth/drive.readonly https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';
                    $queryBuildArray['response_type'] = 'code';
                    $queryBuildArray['access_type']   = 'offline';
                    $queryBuildArray['prompt']        = 'consent';
                    $auth_login_url                   = $auth_login_url;
                }else{
                    $queryBuildArray['grant_type']     = 'authorization_code';
                    $queryBuildArray['client_secret']  = $clientSecret;
                    $queryBuildArray['code']           = $code;
                    $client      = new Client(); 
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
                $queryBuildArray['client_id'] = $clientId;
                $queryBuildArray['redirect_uri'] = $redirect_uri;
                if ($is_handle_callback == false) {
                    $queryBuildArray['scope'] = 'ManageLists,ImportSubscribers,ViewSubscribersInReports';
                    $queryBuildArray['response_type'] = 'code';
                    $auth_login_url = $auth_login_url;
                } else {
                    $queryBuildArray['grant_type'] = 'authorization_code';
                    $queryBuildArray['client_secret'] = $clientSecret;
                    $queryBuildArray['code'] = $code;
                    $client = new Client();
                    $accessToken = self::getAccessTokenOftool($client, $token_url, $queryBuildArray, $toolName);
                    return $accessToken;
                }
                break;
            break;
            case ToolNameEnum::AWEBER:
                $queryBuildArray['client_id']     = $clientId;
                $queryBuildArray['redirect_uri']  = $redirect_uri; 
                if($is_handle_callback == false){
                    $codeVerifier  = self::generateCodeVerifier();
                    $codeChallenge = self::generateCodeChallenge($codeVerifier);
                    Session::put('aweber_code_verifier', $codeVerifier);
                    $queryBuildArray['response_type']         = 'code';
                    $queryBuildArray['scope']                 = 'account.read list.read subscriber.read subscriber.write';
                    // $queryBuildArray['code_challenge'] = $codeChallenge;
                    // $queryBuildArray['code_challenge_method'] = 'S256';
                    $auth_login_url                           = $auth_login_url; 
                } else {

                    $queryBuildArray['grant_type']     = 'authorization_code';
                    $queryBuildArray['client_secret']  = $clientSecret;
                    $codeVerifierFromSession           = Session::pull('aweber_code_verifier');  
                    $queryBuildArray['code']           = $code; 
                    $queryBuildArray['code_verifier']  = $codeVerifierFromSession;  
                    
                    $client                            = new Client();
                    $accessToken                       = self::getAccessTokenOftool($client, $token_url, $queryBuildArray, $toolName);
                    return $accessToken;
                }
            break;
            case ToolNameEnum::CONSTANTCONTACT: // Added for Constant Contact
                $queryBuildArray['client_id']     = $clientId;
                $queryBuildArray['redirect_uri']  = $redirect_uri;
                if($is_handle_callback == false){
                    $queryBuildArray['response_type'] = 'code';
                    $queryBuildArray['scope']         = 'contact_data campaign_data account_read offline_access'; // Example scopes, adjust as needed
                     $queryBuildArray['state']        = Str::random(16);
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
            case ToolNameEnum::INTERCOM: // Added for Intercom
                $queryBuildArray['client_id']     = $clientId;
                $queryBuildArray['redirect_uri']  = $redirect_uri;
                if($is_handle_callback == false){
                    $queryBuildArray['response_type'] = 'code';
                    $queryBuildArray['scope']         = 'read_users write_users'; // Scopes for reading and writing users/contacts
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
            case ToolNameEnum::ZOHOCAMPAIGN: 
                 $queryBuildArray['client_id']   = $clientId;
                $queryBuildArray['redirect_uri'] = $redirect_uri;
                if ($is_handle_callback == false) {
                    // Removed 'AaaServer.profile.Read' as it was causing the error
                    $queryBuildArray['scope']         = 'ZohoCampaigns.campaign.ALL,ZohoCampaigns.contact.ALL,AaaServer.profile.Read';
                    $queryBuildArray['response_type'] = 'code';
                    $queryBuildArray['access_type']   = 'offline'; // For refresh tokens
                    $queryBuildArray['prompt']        = 'consent'; // To ensure consent screen is shown
                    $auth_login_url                   = $auth_login_url;
                } else {
                    $queryBuildArray['grant_type']    = 'authorization_code';
                    $queryBuildArray['client_secret'] = $clientSecret;
                    $queryBuildArray['code']          = $code;
                    $client                           = new Client();
                    $accessToken                      = self::getAccessTokenOftool($client, $token_url, $queryBuildArray, $toolName);
                    return $accessToken;
                }
            break;
            case ToolNameEnum::DRIP: // Added for Drip
                $queryBuildArray['client_id'] = $clientId;
                $queryBuildArray['redirect_uri'] = $redirect_uri;
                if ($is_handle_callback == false) {
                    $queryBuildArray['response_type'] = 'code'; 
                    $auth_login_url = $auth_login_url;
                } else {
                    $queryBuildArray['grant_type'] = 'authorization_code';
                    $queryBuildArray['client_secret'] = $clientSecret;
                    $queryBuildArray['code'] = $code;
                    $client = new Client();
                    $accessToken = self::getAccessTokenOftool($client, $token_url, $queryBuildArray, $toolName);
                    return $accessToken;
                }
            break;
            default:
                return;
        }
        
        $query = http_build_query($queryBuildArray);
        // pp("$auth_login_url?$query");
        return redirect("$auth_login_url?$query");  
    }

    private static function generateCodeVerifier()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
    private static function generateCodeChallenge($codeVerifier)
    {
        return rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
    }

    private static function getAccessTokenOftool($client, $token_url, $queryBuildArray,$toolname){
         try {
            $param      = ['form_params' => $queryBuildArray];
            $response   = $client->post("$token_url", $param);
            $data       = json_decode($response->getBody(), true);

            if ($toolname == ToolNameEnum::HUBSPOT || $toolname == ToolNameEnum::DROPBOX ||  $toolname == ToolNameEnum::GOOGLESHEETS || $toolname == ToolNameEnum::CAMPAIGNMONITOR ||ToolNameEnum::CONSTANTCONTACT ||  $toolname == ToolNameEnum::AWEBER || $toolname == ToolNameEnum::ZOHOCAMPAIGN | $toolname == ToolNameEnum::DRIP) { 
                return $data;
            } else { 
                return $data['access_token'];
            }
            return $accessToken;
        } catch (ClientException $e) {
            $responseBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';
            // pp($responseBody);
            Log::error("ClientException getting access token for $toolname: " . $e->getMessage() . " Response: " . $responseBody);
            throw $e; // Re-throw to be caught by handleCallback
        } catch (\Exception $e) {
            // pp($e->getMessage());
            Log::error("Exception getting access token for $toolname: " . $e->getMessage());
            throw $e; // Re-throw to be caught by handleCallback
        }
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
        switch ($toolName) {
            case ToolNameEnum::DROPBOX:
            case ToolNameEnum::GOOGLESHEETS:
            case ToolNameEnum::CONSTANTCONTACT:
            case ToolNameEnum::CAMPAIGNMONITOR: 
            case ToolNameEnum::HUBSPOT: 
            case ToolNameEnum::DRIP:
                $header = ["Authorization" => "Bearer $accessToken"];
            break;
            case ToolNameEnum::MAILCHIMP:
                $header = ["Authorization"=> "OAuth $accessToken"];
            break;
            case ToolNameEnum::ZOHOCAMPAIGN:
                $header = ["Authorization" => "Zoho-oauthtoken $accessToken"];
            break; 
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
        if($toolName=='googlesheet'){
            $toolName = 'google sheets';
        }
        else if($toolName=='campaignmonitor'){
            $toolName = 'campaign monitor';
        }else if($toolName=='constantcontact'){
            $toolName = 'constant contact';   
        }else if($toolName=='zoho'){
            $toolName = 'zoho campaign';   
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
                // pp($accessTokenData);
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
                        $mc_user_id  = $meta['sub'];
                        $accountName = $meta['name'] ?? null;
                        $email       = $meta['email'] ?? null;
                        $mc_dc       = $meta['picture'] ?? null;
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
                    case ToolNameEnum::CONSTANTCONTACT: // Added for Constant Contact
                        if (isset($accessTokenData['access_token'])) {
                            $accessToken = $accessTokenData['access_token'];
                            $refreshToken = $accessTokenData['refresh_token'] ?? null;
                        } else {
                            Session::flash('error', "Something went wrong with the access token for $originalToolName.");
                            return redirect('/tools');
                        }
                        $ccClient = new Client(['base_uri' => $BASE_API_URL]);
                        // pp($AUTH_METADATA_URL);
                        $meta     = self::getMetadataOfTool($ccClient, $AUTH_METADATA_URL, $accessToken, $toolName);
                        if (!$meta) {
                            Session::flash('error', "Failed to retrieve metadata for $originalToolName.");
                            return redirect('/tools');
                        }
                        // Constant Contact metadata typically returns an array of accounts, or a single account object
                        // Assuming the first account or a direct account object
                        $accountInfo = $meta[0] ?? $meta; // Adjust based on actual CC API response structure
                        $mc_user_id  = $accountInfo['encoded_account_id'] ?? null; // Use account_id
                        $accountName = $accountInfo['organization_name'] ?? ($accountInfo['first_name'] . ' ' . $accountInfo['last_name'] ?? null);
                        $email       = $accountInfo['contact_email'] ?? null;
                        $mc_dc       = null; // Not applicable for Constant Contact
                    break;
                    case ToolNameEnum::AWEBER:
                        if (isset($accessTokenData['access_token'])) {
                            $accessToken  = $accessTokenData['access_token'];
                            $refreshToken = $accessTokenData['refresh_token'] ?? null;
                        } else {
                            Session::flash('error', "Something went wrong with the access token for $originalToolName.");
                            return redirect('/tools');
                        }
                        try {
                            $aweberClient = new Client([
                                'base_uri' => $BASE_API_URL,
                                'headers' => ['Authorization' => "Bearer $accessToken"],
                            ]);

                            $accountsResponse = $aweberClient->get('accounts');
                            $accounts = json_decode($accountsResponse->getBody(), true);

                            // Fix: AWeber accounts are in 'entries' key
                            if (empty($accounts['entries']) || !isset($accounts['entries'][0])) {
                                Session::flash('error', "No AWeber accounts found for this user.");
                                return redirect('/tools');
                            }
                            
                            $primaryAccount = $accounts['entries'][0];
                            $mc_user_id    = $primaryAccount['id'];
                            $accountName   = $primaryAccount['name'] ?? 'AWeber Account';
                            $email         = $primaryAccount['email'] ?? null; 
                            $mc_dc         = null; 
                            $meta          = $primaryAccount; 

                        } catch (\Exception $e) {
                            Log::error("Failed to retrieve AWeber account data: " . $e->getMessage());
                            Session::flash('error', "Failed to retrieve AWeber account data. " . $e->getMessage());
                            return redirect('/tools');
                        }
                    break;
                    case ToolNameEnum::ZOHOCAMPAIGN:
                        if (isset($accessTokenData['access_token'])) {
                            $accessToken = $accessTokenData['access_token'];
                            $refreshToken = $accessTokenData['refresh_token'] ?? null;
                        } else {
                            Session::flash('error', "Something went wrong with the access token for $originalToolName.");
                            return redirect('/tools');
                        }
                        $zohoCampaignsClient = new Client(['base_uri' => $BASE_API_URL]);
                        $meta = self::getMetadataOfTool($zohoCampaignsClient, $AUTH_METADATA_URL, $accessToken, $toolName);
                        if (!$meta) {
                            Session::flash('error', "Failed to retrieve metadata for $originalToolName.");
                            return redirect('/tools');
                        } 
                        // Zoho Campaign user info is typically nested under 'users' or directly in the response
                        $userInfo    = $meta['users'][0] ?? $meta; // Adjust based on actual Zoho API response structure
                        $mc_user_id  = $userInfo['ZUID'] ?? null; // Zoho User ID
                        $accountName = $userInfo['Display_Name'] ?? ($userInfo['First_Name'] . ' ' . $userInfo['Last_Name'] ?? 'Zoho Campaign Account');
                        $email       = $userInfo['Email'] ?? null;
                        $mc_dc       = null; // Not applicable for Zoho Campaign in this context
                    break;
                    case ToolNameEnum::DRIP: // Handle Drip callback
                    if (isset($accessTokenData['access_token'])) {
                        $accessToken = $accessTokenData['access_token'];
                        $refreshToken = $accessTokenData['refresh_token'] ?? null;
                    } else {
                        Session::flash('error', "Something went wrong with the access token for $originalToolName.");
                        return redirect('/tools');
                    }
                    try {
                        $dripClient = new Client([
                            'base_uri' => $BASE_API_URL,
                            'headers' => ['Authorization' => "Bearer $accessToken"],
                        ]);

                        $accountsResponse = $dripClient->get('accounts');
                        $accounts = json_decode($accountsResponse->getBody(), true);

                        if (empty($accounts['accounts']) || !isset($accounts['accounts'][0])) {
                            Session::flash('error', "No Drip accounts found for this user.");
                            return redirect('/tools');
                        } 
                        $primaryAccount = $accounts['accounts'][0];
                        $mc_user_id     = $primaryAccount['id'];
                        $accountName    = $primaryAccount['name'] ?? 'Drip Account';
                        $email          = null;
                        $mc_dc          = null;
                        $meta           = $primaryAccount;
                    } catch (\Exception $e) {
                        Log::error("Failed to retrieve Drip account data: " . $e->getMessage());
                        Session::flash('error', "Failed to retrieve Drip account data. " . $e->getMessage());
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
                case ToolNameEnum::CONSTANTCONTACT: // Added for Constant Contact
                    $client = new Client([
                        'base_uri'    => $base_api_url,
                        'headers'     => self::createClientUrlWithHeadBasedOnTools(ToolNameEnum::CONSTANTCONTACT, $accessToken),
                        'http_errors' => false, // Handle errors manually to check for 401
                    ]);

                    // Constant Contact uses /v3/contacts for email lists
                    $response = $client->get('contacts'); // Get contacts (emails)
                    $statusCode = $response->getStatusCode();

                    if ($statusCode === 401) {
                        // Attempt token refresh 
                        if (!empty($integration->mc_refresh_token)) {
                            $queryBuildArray = [
                                'grant_type'    => 'refresh_token',
                                'client_id'     => $toolData->client_id,
                                'client_secret' => $toolData->client_secret,
                                'refresh_token' => $integration->mc_refresh_token,
                                'redirect_uri'  => $urlJson['redirect_url'],
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
                                    'headers'     => self::createClientUrlWithHeadBasedOnTools(ToolNameEnum::CONSTANTCONTACT, $accessToken),
                                    'http_errors' => true,
                                ]);
                                $response = $client->get('contacts');  
                                $statusCode = $response->getStatusCode();
                            } else {
                                DB::rollBack();
                                return response()->json([
                                    'message' => 'Unauthorized',
                                    'success' => false,
                                    'error' => 'Failed to refresh access token for Constant Contact.'
                                ], 401);
                            }
                        } else {
                            DB::rollBack();
                            return response()->json([
                                'message' => 'Unauthorized',
                                'success' => false,
                                'error' => 'No refresh token available for Constant Contact. Please re-authenticate.'
                            ], 401);
                        }
                    }

                    if ($statusCode !== 200) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Failed to retrieve Constant Contact contacts.',
                            'success' => false,
                            'error' => $response->getBody()->getContents()
                        ], $statusCode);
                    }

                    $contacts = json_decode($response->getBody(), true);
                    if (empty($contacts['contacts'])) { // Constant Contact API returns contacts in 'contacts' key
                        DB::rollBack();
                        return response()->json([
                            'message' => 'No contacts found for this Constant Contact account.',
                            'success' => false,
                            'error' => 'No Constant Contact contacts available'
                        ], 404);
                    }
                    $emails = array_column($contacts['contacts'], 'email_address');
                    $listId = 'all_contacts'; // Constant Contact doesn't have a single "list" ID like Mailchimp for all contacts. Use a placeholder.
                break;
                case ToolNameEnum::ZOHOCAMPAIGN:
                    $client = new Client([
                        'base_uri' => $base_api_url,
                        'headers' => self::createClientUrlWithHeadBasedOnTools(ToolNameEnum::ZOHOCAMPAIGN, $accessToken),
                        'http_errors' => false,
                    ]);
                    // pp($accessToken);
                    // Zoho Campaigns uses 'getmailinglists' to get lists
                    $response = $client->get('getmailinglists?resfmt=JSON');
                    $statusCode = $response->getStatusCode(); 
                    if ($statusCode === 401) {
                        // Attempt token refresh
                        if (!empty($integration->mc_refresh_token)) {
                            $queryBuildArray = [
                                'grant_type' => 'refresh_token',
                                'client_id' => $toolData->client_id,
                                'client_secret' => $toolData->client_secret,
                                'refresh_token' => $integration->mc_refresh_token,
                                'redirect_uri' => $urlJson['redirect_url'],
                            ];
                            $accessTokenData = self::getAccessTokenOftool($client, $token_url, $queryBuildArray, $slug);
                            if (!empty($accessTokenData) && isset($accessTokenData['access_token'])) {
                                $integration->mc_token = $accessTokenData['access_token'];
                                $integration->mc_refresh_token = $accessTokenData['refresh_token'] ?? $integration->mc_refresh_token;
                                $integration->save();
                                $accessToken = $accessTokenData['access_token'];

                                // Retry with new token
                                $client = new Client([
                                    'base_uri' => $base_api_url,
                                    'headers' => self::createClientUrlWithHeadBasedOnTools(ToolNameEnum::ZOHOCAMPAIGN, $accessToken),
                                    'http_errors' => true,
                                ]);
                                $response = $client->get('getmailinglists?resfmt=JSON');
                                $statusCode = $response->getStatusCode();
                            } else {
                                DB::rollBack();
                                return response()->json([
                                    'message' => 'Unauthorized',
                                    'success' => false,
                                    'error' => 'Failed to refresh access token for Zoho Campaign.'
                                ], 401);
                            }
                        } else {
                            DB::rollBack();
                            return response()->json([
                                'message' => 'Unauthorized',
                                'success' => false,
                                'error' => 'No refresh token available for Zoho Campaign. Please re-authenticate.'
                            ], 401);
                        }
                    }

                    if ($statusCode !== 200) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Failed to retrieve Zoho Campaign mailing lists.',
                            'success' => false,
                            'error' => $response->getBody()->getContents()
                        ], $statusCode);
                    } 
                    $lists = json_decode($response->getBody(), true); 
                    if (empty($lists['list_of_details'])) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'No mailing lists found for this Zoho Campaign account.',
                            'success' => false,
                            'error' => 'No Zoho Campaign mailing lists available'
                        ], 404);
                    }

                    // For simplicity, get subscribers from the first list.
                    $listId = $lists['list_of_details'][0]['listkey'];
                   
                    $subscribersResponse = $client->get("getlistsubscribers?listkey={$listId}&resfmt=JSON");
                    $subscribers = json_decode($subscribersResponse->getBody(), true);
                    if (empty($subscribers['list_of_details'])) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'No subscribers found in the selected Zoho Campaign mailing list.',
                            'success' => false,
                            'error' => 'No subscribers available in the list'
                        ], 404);
                    }
                    $emails = array_column($subscribers['list_of_details'], 'contact_email');
                break;
                case ToolNameEnum::DRIP: // Fetch Drip subscribers
                    $client = new Client([
                        'base_uri' => $base_api_url,
                        'headers' => self::createClientUrlWithHeadBasedOnTools(ToolNameEnum::DRIP, $accessToken),
                        'http_errors' => false,
                    ]);

                    $response = $client->get($integration->mc_user_id . '/subscribers');
                    $statusCode = $response->getStatusCode();
                    if ($statusCode === 401) {
                        if (!empty($integration->mc_refresh_token)) {
                            $queryBuildArray = [
                                'grant_type' => 'refresh_token',
                                'client_id' => $toolData->client_id,
                                'client_secret' => $toolData->client_secret,
                                'refresh_token' => $integration->mc_refresh_token,
                            ];
                            $accessTokenData = self::getAccessTokenOftool($client, $token_url, $queryBuildArray, $slug);

                            if (!empty($accessTokenData) && isset($accessTokenData['access_token'])) {
                                $integration->mc_token = $accessTokenData['access_token'];
                                $integration->mc_refresh_token = $accessTokenData['refresh_token'] ?? $integration->mc_refresh_token;
                                $integration->save();
                                $accessToken = $accessTokenData['access_token'];

                                $client = new Client([
                                    'base_uri' => $base_api_url,
                                    'headers' => self::createClientUrlWithHeadBasedOnTools(ToolNameEnum::DRIP, $accessToken),
                                    'http_errors' => true,
                                ]);
                                $response   = $client->get($integration->mc_user_id . '/subscribers');
                                $statusCode = $response->getStatusCode();
                            } else {
                                DB::rollBack();
                                return response()->json([
                                    'message' => 'Unauthorized',
                                    'success' => false,
                                    'error' => 'Failed to refresh access token for Drip.'
                                ], 401);
                            }
                        } else {
                            DB::rollBack();
                            return response()->json([
                                'message' => 'Unauthorized',
                                'success' => false,
                                'error' => 'No refresh token available for Drip. Please re-authenticate.'
                            ], 401);
                        }
                    }

                    if ($statusCode !== 200) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Failed to retrieve Drip campaigns.',
                            'success' => false,
                            'error' => $response->getBody()->getContents()
                        ], $statusCode);
                    }

                    $campaigns = json_decode($response->getBody(), true); 
                    $listId    = NULL;
                    if (empty($campaigns['subscribers'])) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'No subscribers found in the selected Drip campaign.',
                            'success' => false,
                            'error' => 'No subscribers available in the campaign'
                        ], 404);
                    } 
                    $emails = array_column($campaigns['subscribers'], 'email');
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
                $record->email                     = ($slug==ToolNameEnum::CONSTANTCONTACT)?$email['address']:$email;
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
            pp($e->getMessage());
            DB::rollBack(); // Roll back DB transaction on error
            return response()->json([
                'message' => 'Something went wrong',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private static function createClientUrlWithHeadBasedOnTools($toolName,$accessToken){
        $headers = [];
        switch (strtolower($toolName)) {
            case ToolNameEnum::MAILCHIMP:
                $headers = [
                    'Authorization' => "OAuth $accessToken",
                    'Accept' => 'application/json',
                ];
            break;
            case ToolNameEnum::HUBSPOT:
            case ToolNameEnum::DRIP:
                $headers = [
                    'Authorization' => "Bearer $accessToken",
                    'Content-Type' => 'application/json',

                ];
            break;
            case ToolNameEnum::CONSTANTCONTACT: // Added for Constant Contact
                $headers = [
                    'Authorization' => "Bearer $accessToken",
                    'Content-Type' => 'application/json',
                ];
            break;
            case ToolNameEnum::ZOHOCAMPAIGN:
                $headers = [
                    'Authorization' => "Zoho-oauthtoken $accessToken",
                    'Accept' => 'application/json',
                ];
            break;
        }
        return $headers;
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
                case ToolNameEnum::CONSTANTCONTACT:
                    $client = new Client([
                        'base_uri' => "$base_api_url",
                        'headers' => self::createClientUrlWithHeadBasedOnTools(ToolNameEnum::CONSTANTCONTACT, $accessToken),
                        'http_errors' => false,
                    ]); 
                    foreach ($emailsToUnsubscribe as $email) {
                        $email          = strtolower(trim($email));  
                        $searchResponse = $client->get("contacts?email=$email");
                        $searchStatus   = $searchResponse->getStatusCode();
                        $searchBody     = json_decode($searchResponse->getBody(), true);
                        if ($searchStatus === 200 && !empty($searchBody['contacts'])) {
                             
                            $contactId = $searchBody['contacts'][0]['contact_id'] ?? null;
                            $verifyResponse = $client->get("contacts/$contactId");
                            $verifyStatus = $verifyResponse->getStatusCode();
                            if ($contactId) {
                                // Confirm contact exists
                                $verifyResponse = $client->get("contacts/$contactId");
                                $verifyStatus = $verifyResponse->getStatusCode();
                                $verifyBody = $verifyResponse->getBody()->getContents();  
                                if ($verifyStatus === 200) { 
                                    $updateResponse = $client->patch("contacts/$contactId", [
                                        'json' => [
                                            'email_address' => [
                                                'address' => $email
                                            ],
                                            'list_memberships' => []
                                        ],
                                    ]);
                                    $updateStatus = $updateResponse->getStatusCode();
                                    $updateBody   = $updateResponse->getBody()->getContents(); 
                                    if ($updateStatus === 200) {
                                        $results[] = ['email' => $email, 'status' => 'unsubscribed'];
                                    } else {
                                        $results[] = ['email' => $email, 'status' => 'failed', 'response' => $updateBody];
                                    }
                                } elseif ($verifyStatus === 404) {
                                    $results[] = ['email' => $email, 'status' => 'already_unsubscribed'];
                                } else {
                                    $results[] = ['email' => $email, 'status' => 'verify_failed', 'response' => $verifyBody];
                                }
                            } else {
                                $results[] = ['email' => $email, 'status' => 'contact_id_missing'];
                            }

                        } elseif ($searchStatus === 404) {
                            $results[] = ['email' => $email, 'status' => 'not_found'];
                        } else {
                            $results[] = ['email' => $email, 'status' => 'failed', 'reason' => 'Search failed'];
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
                'message' => 'Invalid integration',
                'error'   =>' Invalid integration',
                'success' => false
            ], 404);
        } 

        $toolData    = $integration->tool;
        $urlJson     = json_decode($toolData->url, true);
        $tokenUrl    = $urlJson['auth_token_url'];
        $baseApiUrl  = $urlJson['base_api_url'];
        $accessToken = $integration->mc_token;
        $slug        = $integration->service_name; 
        // Base Client without specific base_uri for the initial check, in case of token refresh needed
        $client = new Client(['http_errors' => false]);
        $response = null;

        try {
            switch ($slug) {
                case ToolNameEnum::GOOGLESHEETS:
                    // Google Drive API base URL
                    $client = new Client([
                        'base_uri' => 'https://www.googleapis.com/',
                        'headers' => ['Authorization' => "Bearer $accessToken"],
                        'http_errors' => false,
                    ]);
                    $response = $client->get('drive/v3/files', [
                        'query' => [
                            'q' => "mimeType='application/vnd.google-apps.spreadsheet'",
                            'fields' => 'files(id, name)',
                        ],
                    ]);
                    break;
                case ToolNameEnum::DROPBOX:
                    // Dropbox API base URL
                    $client = new Client([
                        'base_uri' => $baseApiUrl,
                        'headers' => ['Authorization' => "Bearer $accessToken"],
                        'http_errors' => false,
                    ]);
                    // Using list_folder with a path and recursive flag
                    $response = $client->post('files/list_folder', [
                        'json' => [
                            'path' => '', // Root folder
                            'recursive' => true,
                        ],
                    ]);
                    break;
                default:
                    throw new \Exception('Unsupported tool for file listing.');
            }

            if ($response->getStatusCode() === 401) {
                // Token refresh logic
                $queryBuildArray = [
                    'grant_type' => 'refresh_token',
                    'client_id' => $toolData->client_id,
                    'client_secret' => $toolData->client_secret,
                    'redirect_uri' => $urlJson['redirect_url'],
                    'refresh_token' => $integration->mc_refresh_token
                ];
                $accessTokenData = self::getAccessTokenOftool(new Client(), $tokenUrl, $queryBuildArray, $slug);

                if (empty($accessTokenData) || !isset($accessTokenData['access_token'])) {
                    throw new \Exception('Failed to refresh access token.');
                }

                $integration->mc_token = $accessTokenData['access_token'];
                if (isset($accessTokenData['refresh_token'])) {
                    $integration->mc_refresh_token = $accessTokenData['refresh_token'];
                }
                $integration->save();
                $accessToken = $integration->mc_token;

                // Re-attempt the request with the new token
                $client = new Client([
                    'base_uri' => ($slug === ToolNameEnum::GOOGLESHEETS) ? 'https://www.googleapis.com/' : $baseApiUrl,
                    'headers' => ['Authorization' => "Bearer $accessToken"],
                    'http_errors' => true,
                ]);

                switch ($slug) {
                    case ToolNameEnum::GOOGLESHEETS:
                        $response = $client->get('drive/v3/files', [
                            'query' => [
                                'q' => "mimeType='application/vnd.google-apps.spreadsheet'",
                                'fields' => 'files(id, name)',
                            ],
                        ]);
                        break;
                    case ToolNameEnum::DROPBOX:
                        $response = $client->post('files/list_folder', [
                            'json' => [
                                'path' => '',
                                'recursive' => true,
                            ],
                        ]);
                        break;
                }
            }

            if ($response->getStatusCode() !== 200) {
                throw new \Exception('API Error: ' . $response->getBody()->getContents());
            }

            $filesData = json_decode($response->getBody()->getContents(), true);
            $files     = [];
            switch ($slug) {
                case ToolNameEnum::GOOGLESHEETS:
                    $files = $filesData['files'] ?? [];
                    break;
                case ToolNameEnum::DROPBOX:
                    $dropboxFiles = $filesData['entries'] ?? [];
                    $excelExtensions = ['xls', 'xlsx', 'xlsm'];
                    foreach ($dropboxFiles as $file) {
                        if ($file['.tag'] === 'file') {
                            $pathinfo = pathinfo($file['name']);
                            if (isset($pathinfo['extension']) && in_array(strtolower($pathinfo['extension']), $excelExtensions)) {
                                $files[] = [
                                    'id' => $file['id'],
                                    'name' => $file['name'],
                                    'path_lower' => $file['path_lower']
                                ];
                            }
                        }
                    }
                    break;
            }

            // return $files;
            return response()->json([
                'message' => 'Dropbox Excel files retrieved successfully.',
                'success' => true,
                'data' => $files
            ], 200);

        } catch (ClientException $e) {
            $responseBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';
            Log::error("ClientException listing files for $slug: " . $e->getMessage() . " Response: " . $responseBody);
            // throw new \Exception('Failed to retrieve files: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error("Exception listing files for $slug: " . $e->getMessage());
            return response()->json([
                'message' => "An error occurred while fetching the $slug files.",
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // public function getDropboxExcelFiles(Request $request)
    // {
    //     $userId           = $request->query('userId');
    //     $integeration_id  = $request->query('toolId');
    //     $toolName         = $request->query('toolName');

    //     if (!$userId || !$integeration_id) {
    //         return response()->json([
    //             'message' => 'Missing parameters',
    //             'success' => false,
    //             'error' => 'userId and integeration_id are required.'
    //         ], 400);
    //     }

    //     $integration = Integration::with('tool')->where('id',$integeration_id)->where('mc_user_id',"$userId")->where('user_id',Auth::user()->id)->where('service_name',strtolower($toolName))->find($integeration_id);
    //     if (empty($integration)) {
    //         return response()->json([
    //             'message' => 'Invalid integration or not a Dropbox integration.',
    //             'success' => false
    //         ], 404);
    //     }

    //     $toolData    = $integration->tool;
    //     $urlJson     = json_decode($toolData->url, true);
    //     $base_api_url = $urlJson['base_api_url']; // This is 'https://api.dropboxapi.com/2/'

    //     try {
    //         $response = $this->handleTokenRefreshAndRetry(
    //             $integration,
    //             $toolData,
    //             $urlJson,
    //             function ($currentAccessToken) use ($base_api_url) {
    //                 $dropboxClient = new Client([
    //                     'base_uri' => $base_api_url,
    //                     'headers' => [
    //                         'Authorization' => "Bearer $currentAccessToken",
    //                         'Content-Type' => 'application/json',
    //                     ],
    //                 ]);
    //                 return $dropboxClient->post('files/list_folder', [
    //                     'json' => [
    //                         'path' => '',
    //                         'recursive' => true,
    //                         'limit' => 2000,
    //                         'include_media_info' => false,
    //                         'include_deleted' => false,
    //                         'include_has_explicit_shared_members' => false,
    //                     ]
    //                 ]);
    //             }
    //         );

    //         $files = json_decode($response->getBody(), true);
    //         $excelFiles = [];

    //         if (isset($files['entries']) && is_array($files['entries'])) {
    //             foreach ($files['entries'] as $entry) {
    //                 if ($entry['.tag'] === 'file') {
    //                     $pathInfo = pathinfo($entry['name']);
    //                     $extension = strtolower($pathInfo['extension'] ?? '');

    //                     if (in_array($extension, ['xls', 'xlsx'])) {
    //                         $excelFiles[] = [
    //                             'id' => $entry['id'],
    //                             'name' => $entry['name'],
    //                             'path_display' => $entry['path_display'],
    //                             'size' => $entry['size'],
    //                         ];
    //                     }
    //                 }
    //             }
    //         }

    //         return response()->json([
    //             'message' => 'Dropbox Excel files retrieved successfully.',
    //             'success' => true,
    //             'data' => $excelFiles
    //         ], 200);

    //     } catch (\Exception $e) {
    //         Log::error("Error in getDropboxExcelFiles: " . $e->getMessage() . " Stack: " . $e->getTraceAsString());
    //         return response()->json([
    //             'message' => 'An error occurred while fetching Dropbox Excel files.',
    //             'success' => false,
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
 
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

    // public function importDropboxExcelFiles(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'integeration_id' => 'required',
    //         'fileName'       => 'required|string', // The full path of the file to download, e.g., "/folder/my_emails.xlsx"
    //         'filePath'       => 'required|string', // The original name of the file
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Validation failed',
    //             'success' => false,
    //             'error' => $validator->errors()
    //         ], 422);
    //     }

    //     $userId             = Auth::user()->id;
    //     $integeration_id    = $request->input('integeration_id');
    //     $fileName           = $request->input('fileName');
    //     $integerationUserId = $request->input('userId');
    //     $filePath           = $request->input('filePath');
    //     $fileId             = $request->input('fileId');

    //     $integration = Integration::with('tool')->where('id',$integeration_id)->where('mc_user_id',"$integerationUserId")->where('user_id',$userId)->find($integeration_id);
    //     if (empty($integration) || strtolower($integration->service_name) !== ToolNameEnum::DROPBOX) {
    //         return response()->json([
    //             'message' => 'Invalid integration or not a Dropbox integration.',
    //             'error' => $validator->errors(),
    //             'success' => false
    //         ], 404);
    //     }

    //     $toolData           = $integration->tool; 
    //     $urlJson            = json_decode($toolData->url, true);
    //     $base_api_url       = $urlJson['base_api_url']; // This is 'https://api.dropboxapi.com/2/' 
    //     $tempFilePath = null; // Initialize to null for cleanup in finally block

    //     try {
    //         DB::beginTransaction();

    //         // Use the new generalized token refresh and retry logic for downloading the file
    //         $response = $this->handleTokenRefreshAndRetry(
    //             $integration,
    //             $toolData,
    //             $urlJson,
    //             function ($currentAccessToken) use ($base_api_url,$filePath) {
    //                 $contentClient = new Client([
    //                     'base_uri' => 'https://content.dropboxapi.com/2/', // Dropbox content API base URL
    //                     'headers' => [
    //                         'Authorization' => "Bearer $currentAccessToken",
    //                         'Dropbox-API-Arg' => json_encode(['path' => $filePath]),
    //                         'Content-Type' => 'text/plain',
    //                     ],
    //                     'stream' => true,
    //                 ]);
    //                 return $contentClient->post('files/download', ['body' => '']);
    //             }
    //         );

    //         $statusCode = $response->getStatusCode(); 
    //         if ($statusCode !== 200) {
    //             DB::rollBack();
    //             $responseBody = $response->getBody()->getContents();
    //             Log::error("Dropbox file download failed after refresh attempt ($statusCode): " . $responseBody);
    //             if ($integration) {
    //                 $integration->status = 'error';
    //                 $integration->save();
    //             }
    //             return response()->json([
    //                 'message' => 'Failed to download file from Dropbox after refresh attempt.',
    //                 'success' => false,
    //                 'error' => $responseBody
    //             ], $statusCode);
    //         }

    //         // Save the downloaded file temporarily
    //         $tempFilePath = tempnam(sys_get_temp_dir(), 'dropbox_excel_') . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
    //         file_put_contents($tempFilePath, $response->getBody()->getContents());
 
    //         $emails            = [];
    //         $importedCount     = 0;
    //         $skippedDuplicates = 0;

    //         // Step 2: Parse the Excel file using PhpSpreadsheet
    //         try {
    //             $readerType  = IOFactory::identify($tempFilePath);
    //             $reader      = IOFactory::createReader($readerType);
    //             $spreadsheet = $reader->load($tempFilePath);
    //             $sheet       = $spreadsheet->getActiveSheet();
    //             $highestRow  = $sheet->getHighestRow();

    //             for ($row = 1; $row <= $highestRow; $row++) {
    //                 $email = trim($sheet->getCell('A' . $row)->getValue()); // Trim whitespace
    //                 if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //                     // Check for duplicates in the current batch and existing in DB
    //                     if (!isset($emails[$email]) && !isset($existingEmails[$email])) {
    //                         $emails[$email] = $email; // Use email as key to prevent duplicates within current batch
    //                     } else {
    //                         $skippedDuplicates++;
    //                         Log::info("Skipped duplicate email: {$email} during Dropbox import.");
    //                     }
    //                 }
    //             }
    //             $emails = array_values($emails);
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             Log::error("Error parsing Excel file from Dropbox: " . $e->getMessage());
    //             // if ($integration) {
    //             //     $integration->status = 'error';
    //             //     $integration->save();
    //             // }
    //             return response()->json([
    //                 'message' => 'Failed to parse Excel file. Ensure it is a valid Excel format and emails are in the first column.',
    //                 'success' => false,
    //                 'error' => $e->getMessage()
    //             ], 500);
    //         }

    //         if (empty($emails)) {
    //             DB::rollBack();
    //             return response()->json([
    //                 'message' => 'No valid emails found in the selected Excel file.',
    //                 'success' => false,
    //                 'error' => 'The selected Excel file does not contain any valid emails in the first column.'
    //             ], 404);
    //         }

    //         // Step 3: Save emails to database
    //         $upload                            = new uploadedAndDownloadFileName();
    //         $upload->fileName                  = $fileName;
    //         $upload->list_id                   = $fileId.'__'.$filePath;
    //         $upload->user_id                   = $userId;
    //         $upload->tool_name                 = $toolData->name;
    //         $upload->tool_id                   = $toolData->id;
    //         $upload->integeration_id           = $integeration_id;
    //         $upload->mc_user_id                = $integration->mc_user_id;
    //         $upload->mc_dc                     = null;
    //         $upload->mc_token                  = $integration->mc_token; // Ensure this is the latest token after potential refresh
    //         $upload->is_tools_integerate_email = '1';
    //         $upload->downloadFileName          = NULL;
    //         $upload->downloadFileLocation      = NULL;
    //         $upload->created_at                = now();
    //         $upload->updated_at                = now();
    //         $upload->save(); // Save to get the $uploadId

    //         $uploadId = $upload->id;

    //         // Define the target directory path within the public disk
    //         $targetDirectory = strtolower($toolData->name) . '/' . now()->format('Y-m-d') . '/' . $uploadId;
    //         $finalFilePath   = $targetDirectory . '/' . $fileName;

    //         // Move the file from temporary location to its final permanent storage location
    //         try {
    //             // Ensure the directory exists
    //             Storage::disk('public')->makeDirectory($targetDirectory);
    //             // Move the file
    //             Storage::disk('public')->putFileAs($targetDirectory, new \Illuminate\Http\File($tempFilePath), $fileName);     
    //             $toolSelected                            = new ToolSelectedFile();
    //             $toolSelected->file_id                   = $fileId;
    //             $toolSelected->file_name                 = $fileName;
    //             $toolSelected->file_path                 = $filePath;
    //             $toolSelected->user_id                   = $userId; 
    //             $toolSelected->tool_id                   = $toolData->id;
    //             $toolSelected->upload_file_path          = $finalFilePath;
    //             $toolSelected->save();  
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             Log::error("Failed to move imported Dropbox Excel file to permanent storage: " . $e->getMessage());
    //             return response()->json([
    //                 'message' => 'Failed to store the imported Excel file permanently.',
    //                 'success' => false,
    //                 'error' => $e->getMessage()
    //             ], 500);
    //         }

    //         $results = [];
    //         foreach ($emails as $email) {
    //             $record                            = new BulkUploadEmailFileData();
    //             $record->email                     = $email;
    //             $record->file_id                   = $uploadId;
    //             $record->importedBy                = $userId;
    //             $record->is_tools_integerate_email = '1';
    //             $record->type                      = 'bulk';
    //             $record->created_at                = now();
    //             $record->updated_at                = now();
    //             $record->save();
    //             $results[]                         = ['email' => $email];
    //             $importedCount++;
    //         }
           
    //         DB::commit();
    //         return response()->json([
    //             'message' => 'Emails imported successfully from Dropbox Excel file!',
    //             'success' => true,
    //             'imported_count' => count($emails),
    //             'results' => $results
    //         ], 201);

    //     } catch (\Exception $e) { 
    //         // $responseBody = $e->getResponse()->getBody()->getContents();
    //         // pp($responseBody);
    //         DB::rollBack();
    //         Log::error("General error importing Dropbox Excel emails: " . $e->getMessage() . " Stack: " . $e->getTraceAsString());
    //         // if ($integration) {
    //         //     // $integration->status = 'error';
    //         //     // $integration->save();
    //         // }
    //         return response()->json([
    //             'message' => 'An unexpected error occurred during Dropbox Excel import.',
    //             'success' => false,
    //             'error' => $e->getMessage()
    //         ], 500);
    //     } finally {
    //         // Ensure the temporary file is always deleted
    //         if ($tempFilePath && file_exists($tempFilePath)) {
    //             unlink($tempFilePath);
    //         }
    //     }
    // }


    public function importDropboxExcelFiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'integeration_id' => 'required',
            'fileName' => 'required|string',
            'filePath' => 'required|string',
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

        $integration = Integration::with('tool')->where('id', $integeration_id)->where('mc_user_id', "$integerationUserId")->where('user_id', $userId)->whereNull('deleted_at')->find($integeration_id);
        if (empty($integration)) {
            return response()->json([
                'message' => 'Invalid integration.',
                'error' => $validator->errors(),
                'success' => false
            ], 404);
        }

        $toolData          = $integration->tool;
        $urlJson           = json_decode($toolData->url, true);
        $base_api_url      = $urlJson['base_api_url'];
        $tempFilePath      = null;
        $uniqueEmails      = [];
        $skippedDuplicates = 0;

        try {
            DB::beginTransaction(); 
            switch (strtolower($integration->service_name)) {
                case ToolNameEnum::DROPBOX:
                    // Original Dropbox logic
                    $response = $this->handleTokenRefreshAndRetry(
                        $integration,
                        $toolData,
                        $urlJson,
                        function ($currentAccessToken) use ($base_api_url, $filePath) {
                            $contentClient = new Client([
                                'base_uri' => 'https://content.dropboxapi.com/2/',
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

                    // Parse the Excel file using PhpSpreadsheet
                    try {
                        $readerType  = IOFactory::identify($tempFilePath);
                        $reader      = IOFactory::createReader($readerType);
                        $spreadsheet = $reader->load($tempFilePath);
                        $sheet       = $spreadsheet->getActiveSheet();
                        $highestRow  = $sheet->getHighestRow();

                        $emails = [];
                        for ($row = 1; $row <= $highestRow; $row++) {
                            $email = trim($sheet->getCell('A' . $row)->getValue());
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $emails[] = $email;
                            } else {
                                Log::info("Skipped invalid email: {$email} during Dropbox import.");
                            }
                        }
                        $uniqueEmails      = array_unique($emails);
                        $skippedDuplicates = count($emails) - count($uniqueEmails);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Error parsing Excel file from Dropbox: " . $e->getMessage());
                        return response()->json([
                            'message' => 'Failed to parse Excel file. Ensure it is a valid Excel format and emails are in the first column.',
                            'success' => false,
                            'error' => $e->getMessage()
                        ], 500);
                    }
                    break;

                case ToolNameEnum::GOOGLESHEETS:
                    // New logic for Google Sheets API
                    $accessToken = $integration->mc_token; // Assumes mc_token holds the access token for Google
                    $spreadsheetId = $fileId;
                    
                    // Fetch the sheet name dynamically as it is not provided
                    try {
                        $sheetRange = $this->fetchFirstSheetNameFromGoogleSheet($spreadsheetId, $base_api_url, $accessToken);
                    } catch (\Exception $e) {
                        return response()->json([
                            'error'   => 'Failed to retrieve sheet name from Google Sheet.',
                            'message' => 'Failed to retrieve sheet name from Google Sheet. ' . $e->getMessage(),
                            'success' => false,
                        ], 500);
                    }
                    $client = new Client([
                        'base_uri' => $base_api_url,
                        'headers' => [
                            'Authorization' => "Bearer {$accessToken}",
                            'Accept' => 'application/json',
                        ],
                    ]);

                    $response      = $client->get("spreadsheets/{$spreadsheetId}/values/{$sheetRange}");
                    $responseData  = json_decode($response->getBody()->getContents(), true);
                    $fullSheetData = $responseData['values'] ?? [];
                    if (empty($responseData['values'])) {
                        return response()->json([
                            'error'   => 'No data found in the selected Google Sheet.',
                            'message' => 'No data found in the selected Google Sheet.',
                            'success' => false,
                        ], 404);
                    }

                    $headerRow        = array_map('strtolower', $responseData['values'][0]);
                    $emailColumnIndex = array_search('email', $headerRow);

                    if ($emailColumnIndex === false) {
                        return response()->json([
                            'error'   => 'Email column not found. Please ensure the first row contains a column with the header "email".',
                            'message' => 'Email column not found. Please ensure the first row contains a column with the header "email".',
                            'success' => false,
                        ], 404);
                    }

                    $emails = [];
                    // Extract and validate emails, skipping the header row
                    for ($i = 1; $i < count($responseData['values']); $i++) {
                        $row = $responseData['values'][$i];
                        if (isset($row[$emailColumnIndex])) {
                            $email = trim($row[$emailColumnIndex]);
                            if ($this->isValidEmail($email)) {
                                $emails[] = $email;
                            } else {
                                Log::warning("Invalid email skipped: " . $email);
                            }
                        }
                    }

                    $uniqueEmails      = array_unique($emails);
                    $skippedDuplicates = count($emails) - count($uniqueEmails); 
                    break; 
                default:
                    return response()->json([
                        'message' => 'Invalid integration tool.',
                        'success' => false
                    ], 400);
            }

            if (empty($uniqueEmails)) {
                DB::rollBack();
                return response()->json([
                    'message' => 'No valid emails found in the selected file.',
                    'success' => false,
                    'error' => 'The selected file does not contain any valid emails.'
                ], 404);
            }
            // Step 3: Save file details and emails to database
            $this->processAndSaveImportedData($uniqueEmails, $fileName, $filePath, $fileId, $userId, $toolData, $integration, $integeration_id, $tempFilePath,$fullSheetData);
            
            DB::commit(); 
            return response()->json([
                'message' => 'Emails imported successfully!',
                'success' => true,
                'imported_count' => count($uniqueEmails),
                'results' => array_map(function ($email) {
                    return ['email' => $email];
                }, array_values($uniqueEmails))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("General error importing emails: " . $e->getMessage() . " Stack: " . $e->getTraceAsString());
            return response()->json([
                'message' => 'An unexpected error occurred during import.',
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        } finally {
            // Ensure the temporary file is always deleted for Dropbox imports
            if ($tempFilePath && file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
        }
    }
 

    /**
     * Fetches the name of the first sheet from a Google Sheet using the Sheets API.
     *
     * @param string $spreadsheetId The ID of the Google Sheet.
     * @param string $baseApiUrl The base URL for the Google Sheets API.
     * @param string $accessToken The access token for the API.
     * @return string The name of the first sheet.
     * @throws \Exception If the API call fails or no sheets are found.
     */
    private function fetchFirstSheetNameFromGoogleSheet(string $spreadsheetId, string $baseApiUrl, string $accessToken): string
    {
        $client = new Client([
            'base_uri' =>  $baseApiUrl,
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
                'Accept' => 'application/json',
            ],
        ]); 
        $response    = $client->get("spreadsheets/{$spreadsheetId}");
        $responseData = json_decode($response->getBody()->getContents(), true);
        if (empty($responseData['sheets'])) {
            throw new \Exception('No sheets found in the Google Sheet.');
        }

        return $responseData['sheets'][0]['properties']['title'];
    }
    /**
     * Encapsulates the logic to save file details and bulk emails to the database.
     *
     * @param array $uniqueEmails
     * @param string $fileName
     * @param string $filePath
     * @param string $fileId
     * @param string $userId
     * @param object $toolData
     * @param object $integration
     * @param int $integeration_id
     * @param string|null $tempFilePath
     * @return void
     */
    private function processAndSaveImportedData($uniqueEmails,$fileName,$filePath,$fileId,$userId,$toolData,$integration,$integeration_id,$tempFilePath = null,$fullSheetData=  []) {
        // Save uploadedAndDownloadFileName record
        $upload                            = new uploadedAndDownloadFileName();
        $upload->fileName                  = $fileName;
        $upload->list_id                   = $fileId . '__' . $filePath;
        $upload->user_id                   = $userId;
        $upload->tool_name                 = $toolData->name;
        $upload->tool_id                   = $toolData->id;
        $upload->integeration_id           = $integeration_id;
        $upload->mc_user_id                = $integration->mc_user_id;
        $upload->mc_dc                     = null;
        $upload->mc_token                  = $integration->mc_token;
        $upload->is_tools_integerate_email = '1';
        $upload->downloadFileName          = null;
        $upload->downloadFileLocation      = null;
        $upload->created_at                = now();
        $upload->updated_at                = now(); 
        $upload->save(); 
        $uploadId                          = $upload->id;

        // Save ToolSelectedFile record
        $toolSelected                   = new ToolSelectedFile();
        $toolSelected->file_id          = $fileId;
        $toolSelected->file_name        = $fileName;
        $toolSelected->file_path        = $filePath;
        $toolSelected->user_id          = $userId;
        $toolSelected->tool_id          = $toolData->id;
        // Determine and store the file path based on the service
        $uploadFilePath = null;
        if ($integration->service_name === ToolNameEnum::DROPBOX) {
            $uploadFilePath = $this->moveAndStoreFile($tempFilePath, $toolData, $uploadId, $fileName);
        } elseif ($integration->service_name === ToolNameEnum::GOOGLESHEETS) { 
            $uploadFilePath = $this->storeDataAsCsv($fullSheetData, $toolData, $uploadId, $fileName);
        }
        $toolSelected->save();

        // Save the unique emails to the BulkUploadEmailFileData table
        $this->saveBulkEmails($uniqueEmails, $uploadId, $userId);
    } 

    /**
     * Creates a CSV file from a list of emails and stores it permanently.
     *
     * @param array $data The array of data rows to save.
     * @param object $toolData The tool data object.
     * @param int $uploadId The ID of the uploaded file record.
     * @param string $fileName The original file name.
     * @return string The final storage path.
     * @throws \Exception If file creation or storage fails.
     */
    private function storeDataAsCsv(array $data, object $toolData, int $uploadId, string $fileName): string
    {
        $tempFilePath = tempnam(sys_get_temp_dir(), 'googlesheet_data_') . '.csv';
        $handle = fopen($tempFilePath, 'w');
        if ($handle === false) {
            throw new \Exception('Failed to create a temporary CSV file.');
        }
    
        // Write the data rows to the temporary CSV file
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
    
        // Use the existing moveAndStoreFile method to move and store the CSV
        try {
            $finalPath = $this->moveAndStoreFile($tempFilePath, $toolData, $uploadId, $fileName . '.csv');
        } finally {
            // Clean up the temporary file
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
        }
    
        return $finalPath;
    }


    /**
     * Moves a file from a temporary location to permanent storage.
     *
     * @param string $tempFilePath
     * @param object $toolData
     * @param int $uploadId
     * @param string $fileName
     * @return string The final storage path.
     */
    private function moveAndStoreFile(string $tempFilePath, object $toolData, int $uploadId, string $fileName): string
    {
        $targetDirectory = strtolower($toolData->name) . '/' . now()->format('Y-m-d') . '/' . $uploadId;
        $finalFilePath = $targetDirectory . '/' . $fileName;

        try {
            Storage::disk('public')->makeDirectory($targetDirectory);
            Storage::disk('public')->putFileAs($targetDirectory, new \Illuminate\Http\File($tempFilePath), $fileName);
        } catch (\Exception $e) {
            Log::error("Failed to move file to permanent storage: " . $e->getMessage());
            throw new \Exception("Failed to store the imported file permanently.");
        }
        return $finalFilePath;
    }

    /**
     * Saves a bulk of emails to the database.
     *
     * @param array $emails The array of emails to save.
     * @param int $uploadId The ID of the uploaded file record.
     * @param string $userId The ID of the user.
     * @return void
     */
    private function saveBulkEmails(array $emails, int $uploadId, string $userId): void
    {
        $emailData = [];
        $now       = now();
        foreach ($emails as $email) {
            $emailData[] = [
                'file_id'                   => $uploadId,
                'email'                     => $email,
                'importedBy'                => $userId,
                'is_tools_integerate_email' => '1',
                'type'                      => 'bulk',
                'created_at'                => $now,
                'updated_at'                => $now,
            ];
        }
        BulkUploadEmailFileData::insert($emailData);
    }

    private function isValidEmail(string $email): bool{
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }



 
    public function toolConnectionBasedOnApiKey(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_key'   => 'required|string',
            'tool_id'   => 'required|integer',
            'tool_name' => 'required|string', // This should be the tool's slug (e.g., 'moosend')
            'api_url'   => 'nullable|url',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()->first(), 'message' => 'Validation error'], 400);
        }

        $apiKey   = $request->input('api_key');
        $toolId   = $request->input('tool_id');
        $toolSlug = strtolower($request->input('tool_name'));
        $userId   = Auth::user()->id;
        $apiUrl   = $request->input('api_url'); // For ActiveCampaign
        try {
            DB::beginTransaction();

            $tool = IntegrationTool::where('id', $toolId)->where('slug', $toolSlug)->first();
            if (!$tool) {
                DB::rollBack();
                return response()->json(['success' => false,'message'=>'Tool not found or mismatched.', 'error' => 'Tool not found or mismatched.'], 404);
            } 
            $urlJson = json_decode($tool->url, true); 
            $config  = $this->getApiKeyVerificationConfig($toolSlug, $urlJson);
            if (!$config) {
                DB::rollBack();
                return response()->json(['success' => false,'message'=>'Unsupported tool for API key connection.', 'error' => 'Unsupported tool for API key connection.'], 400);
            }
            $baseApiUrlToUse = $config['base_api_url'];
            if (in_array($toolSlug, [ToolNameEnum::ACTIVECAMPAIGN]) && !empty($apiUrl)) {
                $baseApiUrlToUse = $apiUrl;
            } elseif (in_array($toolSlug, [ToolNameEnum::ACTIVECAMPAIGN]) && empty($apiUrl)) {
                DB::rollBack();
                return response()->json(['success' => false, 'error' => "{$tool->name} API URL is required.",'message'=>"{$tool->name} API URL is required."], 400);
            }

            // Check if an integration with this API key already exists for the user and tool
            $existingIntegration = Integration::where('user_id', $userId)->where('tool_id', $toolId)->where('service_name', $toolSlug)->where('mc_token', $apiKey)->first();

            if ($existingIntegration) {
                DB::rollBack();
                return response()->json(['success' => false, 'error' => "This {$tool->name} account is already connected with this API key."], 409); // 409 Conflict
            }

            $client         = new Client();
            $requestOptions = $config['request_options']($apiKey);

            // Make the API call to verify the API key

            $testResponse = $client->get($baseApiUrlToUse . $config['verify_endpoint'], $requestOptions);
            $responseData = json_decode($testResponse->getBody()->getContents(), true);
            // Check for success based on tool-specific logic
            if ($config['success_check']($responseData)) {
                $accountInfo  = $config['extract_account_info']($responseData);
                $mcUserId     = $apiKey;   
                if( $toolSlug == ToolNameEnum::GETRESPONSE){
                    $accountName  = $accountInfo['metadata']['companyName'] ? $accountInfo['metadata']['companyName']  : ($accountInfo['metadata']['firstName'] ? $accountInfo['metadata']['firstName'].' '.$accountInfo['metadata']['lastName']:nULL);
                    $mcUserId     = $accountInfo['mc_user_id'];
                    $metadata     = $accountInfo['metadata']; 
                    $accountEmail = $accountInfo['account_email']; 
                }
                else if( $toolSlug == ToolNameEnum::MOOSEND){
                    $accountName  = $accountInfo['account_name'];
                    $mcUserId     = hash('sha256', $apiKey);
                    $metadata     = $responseData; 
                }else if($toolSlug == ToolNameEnum::ACTIVECAMPAIGN){
                    $accountName         = $accountInfo['metadata']['user']? $accountInfo['metadata']['user']['firstName'].' '.$accountInfo['metadata']['user']['lastName'] :  $accountInfo['account_name'];
                    $mcUserId            = $accountInfo['mc_user_id'];
                    $metadata            = $accountInfo['metadata']; 
                    $metadata['api_url'] = $baseApiUrlToUse;
                }else if($toolSlug == ToolNameEnum::BREVO){
                    $accountName         = $accountInfo['metadata']? $accountInfo['metadata']['firstName'].' '.$accountInfo['metadata']['lastName'] :  $accountInfo['account_name'];
                    $mcUserId            = $accountInfo['mc_user_id'];
                    $metadata            = $accountInfo['metadata']; 
                }else if($toolSlug == ToolNameEnum::CONVERTKIT){
                    $accountName         = $accountInfo['account_name']? $accountInfo['account_name'] : 'ConvertKit Account';
                    $mcUserId            = $accountInfo['mc_user_id'];
                    $metadata            = $accountInfo['metadata'];    
                }else if($toolSlug == ToolNameEnum::BENCHMARK || $toolSlug == ToolNameEnum::MAILERLITE){
                    $accountName         = $accountInfo['account_name'];
                    $mcUserId            = $accountInfo['mc_user_id']?? hash('sha256', $mcUserId);
                    $metadata            = $accountInfo['metadata'];    
                }else if ($toolSlug == ToolNameEnum::GIST) { // Added for Gist
                    $accountName  = $accountInfo['account_name'];
                    $mcUserId     = $accountInfo['mc_user_id']  ?? hash('sha256', $mcUserId);
                    $metadata     = $accountInfo['metadata']; 
                }
                else if ($toolSlug == ToolNameEnum::MAILGUN) { // Added for Mailgun
                    $accountName  = $accountInfo['account_name'];
                    $mcUserId     = $accountInfo['mc_user_id'] ?? hash('sha256', $mcUserId);;
                    $metadata     = $accountInfo['metadata']; 
                }
                $accountEmail              = $accountInfo['account_email']; 
                $integration               = new Integration();
                $integration->user_id      = $userId;
                $integration->tool_id      = $toolId;
                $integration->service_name = $toolSlug; 
                $integration->mc_token     = $apiKey; // Store the API key 
                $integration->mc_user_id   = $mcUserId;
                $integration->status       = 'verified';
                $integration->name         = $accountName;
                $integration->emails       = $accountEmail;
                $integration->metadata     = json_encode($metadata);
                $integration->save(); 
                DB::commit();
                return response()->json(['success' => true, 'message' => "{$tool->name} connected successfully!"]); 
            } else {
                $errorMessage = $config['error_message_extractor']($responseData);
                DB::rollBack();
                return response()->json(['success' => false, 'error' => $errorMessage], 400);
            }

        } catch (ClientException $e) {
            DB::rollBack(); 
            $responseBody = $e->getResponse()->getBody()->getContents();
             pp($e->getMessage());
            Log::error("API Key verification Guzzle Client error for {$toolSlug}: " . $e->getMessage() . " Response: " . $responseBody);
            $errorMessage = (json_decode($responseBody)->Error->Message ?? $e->getMessage());
            return response()->json(['success' => false, 'error' => "API Key verification failed: " . $errorMessage], $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack(); 
            pp($e->getMessage());
            Log::error("Error connecting {$toolSlug} via API key: " . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'An unexpected error occurred while connecting via API key.'], 500);
        }
    }

    private function getApiKeyVerificationConfig($toolSlug, $urlJson)
    {
        switch ($toolSlug) {
            case ToolNameEnum::MOOSEND:
                return [
                    'base_api_url' => $urlJson['base_api_url'] ?? 'https://api.moosend.com/',
                    'verify_endpoint' => 'lists.json', // Changed to lists.json
                    'request_options' => function($apiKey) {
                        return ['query' => ['apikey' => $apiKey]];
                    },
                    'success_check' => function($responseData) {
                        // Check for successful API response structure (Code 0 and Context present)
                        return isset($responseData['Code']) && $responseData['Code'] === 0 && isset($responseData['Context']);
                    },
                    'extract_account_info' => function($responseData) {
                        // Attempt to extract account info from Context.Account, which might be present in lists.json response
                        $accountInfo = $responseData['Context']['Account'] ?? [];
                        return [
                            'mc_user_id' => $accountInfo['ID'] ?? null,
                            'account_name' => $accountInfo['Name'] ?? 'Moosend Account',
                            'account_email' => $accountInfo['Email'] ?? null,
                            'metadata' => $accountInfo,
                        ];
                    },
                    'error_message_extractor' => function($responseData) {
                        return $responseData['Error']['Message'] ?? 'Invalid API Key or unable to connect.';
                    }
                ];
            case ToolNameEnum::GETRESPONSE:
                return [
                    'base_api_url' => $urlJson['base_api_url'] ?? 'https://api.getresponse.com/v3/',
                    'verify_endpoint' => 'accounts', // Endpoint to verify API key and get account info
                    'request_options' => function($apiKey) {
                        // Trim the API key to remove any leading/trailing whitespace
                        $trimmedApiKey = trim($apiKey);
                        return ['headers' => ['X-Auth-Token' => "api-key $trimmedApiKey"]];
                    },
                    'success_check' => function($responseData) {
                        return isset($responseData['accountId']); // Check for a key that indicates a successful account fetch
                    },
                    'extract_account_info' => function($responseData) {
                        return [
                            'mc_user_id' => $responseData['accountId'] ?? null,
                            'account_name' => ($responseData['firstName'] ?? '') . ' ' . ($responseData['lastName'] ?? 'GetResponse Account'),
                            'account_email' => $responseData['email'] ?? null,
                            'metadata' => $responseData,
                        ];
                    },
                    'error_message_extractor' => function($responseData) {
                        // Provide a more specific error message for GetResponse
                        return $responseData['message'] ?? 'Invalid API Key or unable to connect to GetResponse. Please ensure your API key is correct and matches the GetResponse account region (e.g., api.getresponse.com or api.getresponse.eu).';
                    }
                ];
            case ToolNameEnum::ACTIVECAMPAIGN:
                return [
                    'base_api_url' => '', // Placeholder, will be set from request input
                    'verify_endpoint' => '/api/3/users/me', // Endpoint to verify API key and get user info
                    'request_options' => function($apiKey) {
                        return ['headers' => ['Api-Token' => $apiKey]]; // ActiveCampaign uses Api-Token for direct API key authentication
                    },
                    'success_check' => function($responseData) {
                        return isset($responseData['user']['id']) && isset($responseData['user']['email']);
                    },
                    'extract_account_info' => function($responseData) {
                        $accountName = 'ActiveCampaign Account';
                        if (isset($responseData['user']['account'])) {
                            $accountName = $responseData['user']['account']['name'] ?? $accountName;
                        }
                        return [
                            'mc_user_id' => $responseData['user']['id'] ?? null,
                            'account_name' => $accountName,
                            'account_email' => $responseData['user']['email'] ?? null,
                            'metadata' => $responseData,
                        ];
                    },
                    'error_message_extractor' => function($responseData) {
                        return $responseData['errors'][0]['message'] ?? 'Invalid ActiveCampaign API Key or unable to connect.';
                    }
                ];
            case ToolNameEnum::BREVO:
                return [
                    'base_api_url' => $urlJson['base_api_url'] ?? 'https://api.brevo.com/v3/',
                    'verify_endpoint' => 'account', // Brevo endpoint to get account details
                    'request_options' => function ($apiKey) {
                        return ['headers' => ['api-key' => $apiKey, 'Accept' => 'application/json']];
                    },
                    'success_check' => function ($responseData) {
                        // Brevo account endpoint returns email on success
                        return isset($responseData['email']);
                    },
                    'extract_account_info' => function ($responseData) {
                        return [
                            'mc_user_id' => md5($responseData['email']), // Using email hash as a unique ID
                            'account_name' => $responseData['companyName'] ?? $responseData['email'],
                            'account_email' => $responseData['email'],
                            'metadata' => $responseData,
                        ];
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Invalid Brevo API Key or unable to connect.';
                    }
                ];
            case ToolNameEnum::MAILERLITE: // Added for MailerLite
                return [
                    'base_api_url' => $urlJson['base_api_url'] ?? 'https://api.mailerlite.com/api/v2/',
                    'verify_endpoint' => 'subscribers', // MailerLite endpoint to get account details
                    'request_options' => function ($apiKey) {
                        return ['headers' => ['X-MailerLite-ApiKey' => $apiKey, 'Accept' => 'application/json']];
                    },
                    'success_check' => function ($responseData) { 
                       return is_array($responseData) && count($responseData) > 0;
                    },
                    'extract_account_info' => function ($responseData) {
                        return [
                            'mc_user_id'    =>  NULL,
                            'account_name'  => 'Mailerlite Account',
                            'account_email' => NULL,
                            'metadata'      => $responseData,
                        ];
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['error']['message'] ?? 'Invalid MailerLite API Key or unable to connect.';
                    }
                ];

            case ToolNameEnum::CONVERTKIT: // Added for ConvertKit
                return [
                    'base_api_url' => $urlJson['base_api_url'] ?? 'https://api.convertkit.com/v3/',
                    'verify_endpoint' => 'account', // ConvertKit endpoint for account info
                    'request_options' => function ($apiKey) {
                        return ['query' => ['api_secret' => $apiKey]]; // ConvertKit uses 'api_secret' as a query parameter
                    },
                    'success_check' => function ($responseData) {
                        // ConvertKit account endpoint returns 'name' and 'primary_email_address' directly
                        // Also check for the absence of a top-level 'error' key or 'message' key indicating an API error
                        return isset($responseData['name']) &&
                               isset($responseData['primary_email_address']) &&
                               !isset($responseData['error']) && // Ensure no top-level 'error' object
                               !isset($responseData['message']); // Ensure no top-level 'message' indicating error
                    },
                    'extract_account_info' => function ($responseData) {
                         return [
                            'mc_user_id' => hash('sha256', $responseData['primary_email_address']), // Using hash of email as unique ID
                            'account_name' => $responseData['name'],
                            'account_email' => $responseData['primary_email_address'],
                            'metadata' => $responseData,
                        ];
                    },
                    'error_message_extractor' => function ($responseData) {
                        // ConvertKit errors often have a 'message' field at the root or within an 'error' object
                        if (isset($responseData['error']['message'])) {
                            return $responseData['error']['message'];
                        }
                        if (isset($responseData['message'])) {
                            return $responseData['message'];
                        }
                        return 'Invalid ConvertKit API Secret or unable to connect. Please check your API secret and try again.';
                    }
                ];
            case ToolNameEnum::BENCHMARK: // Added for Benchmark
                return [
                    'base_api_url' => $urlJson['base_api_url'] ?? 'https://clientapi.benchmarkemail.com/', // Updated base URL
                    'verify_endpoint' => 'Contact/', // Updated endpoint for user details
                    'request_options' => function ($apiKey) {
                        return ['headers' => ['AuthToken' => $apiKey, 'Accept' => 'application/json']];
                    },
                    'success_check' => function ($responseData) { 
                        // Check for successful API response structure for Client/ProfileDetails
                        return isset($responseData['Response']['Data']) && isset($responseData['Response']['Count']);
                    },
                    'extract_account_info' => function ($responseData) {
                        return [
                            'mc_user_id'    =>  NULL, // Use ClientID as user ID
                            'account_name'  => 'Benchmark Account',
                            'account_email' =>  NULL,
                            'metadata' => $responseData,
                        ];
                    },
                    'error_message_extractor' => function ($responseData) {
                        // Benchmark errors might have a 'Message' field or be a simple string
                        return $responseData['Message'] ?? 'Invalid Benchmark API Key or unable to connect. Please check your API key.';
                    }
                ];
            case ToolNameEnum::GIST: // Added for Gist
                return [
                    'base_api_url' => $urlJson['base_api_url'] ?? 'https://api.getgist.com/',
                    'verify_endpoint' => 'segments', // Gist endpoint to get current user/account details
                    'request_options' => function ($apiKey) {
                        return ['headers' => ['Authorization' => "Bearer $apiKey"]];
                    },
                    'success_check' => function ($responseData) { 
                       return isset($responseData['segments']) && is_array($responseData['segments']);
                    },
                    'extract_account_info' => function ($responseData) {
                        return [
                            'mc_user_id'     => null, // Using a hash of the API key as a unique identifier
                            'account_name'   => 'Gist Account', // Generic name as user details aren't directly available here
                            'account_email'  => null, // Email not directly available from segments endpoint
                            'metadata'       => $responseData,
                        ];
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Invalid Gist API Key or unable to connect. Please check your API key.';
                    }
                ];

            case ToolNameEnum::MAILGUN: // Added for Mailgun
                return [
                    'base_api_url' => $urlJson['base_api_url'] ?? 'https://api.mailgun.net/v3/', // Default to US region
                    'verify_endpoint' => 'domains', // Endpoint to list domains
                    'request_options' => function ($apiKey) {
                        return ['auth' => ['api', $apiKey]]; // Mailgun uses Basic Auth with 'api' as username
                    },
                    'success_check' => function ($responseData) {
                        // Mailgun returns domains in an 'items' array
                        return isset($responseData['items']) && is_array($responseData['items']);
                    },
                    'extract_account_info' => function ($responseData) {
                        // Extract account info. Mailgun doesn't have a direct 'account' endpoint like some others.
                        // We'll use the first domain's name as a proxy for account name if available, or a generic name.
                        $accountName = 'Mailgun Account';
                        if (!empty($responseData['items'])) {
                            $accountName = $responseData['items'][0]['name'] ?? $accountName;
                        }
                        return [
                            'mc_user_id' =>  NULL, // Use hash of API key as unique ID
                            'account_name' => $accountName,
                            'account_email' => null, // Email not directly available from domains endpoint
                            'metadata' => $responseData,
                        ];
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Invalid Mailgun API Key or unable to connect. Please check your API key and ensure the correct region API URL is used.';
                    }
                ];
            default:
            return null;
        }
    }

    public function fetchApiKeyBasedListing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'integeration_id' => 'required|integer',
            'toolName'        => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'success' => false,
                'error' => $validator->errors()->first()
            ], 400);
        }

        $integeration_id = $request->input('integeration_id');
        $toolName        = strtolower($request->input('toolName'));
        $loggedInUserId  = Auth::user()->id;
        $integration     = Integration::with('tool')->where('id', $integeration_id)->where('user_id', $loggedInUserId)->where('service_name', $toolName)->whereNull('deleted_at')->first();
        if (!$integration) {
            return response()->json(['success' => false, 'message' => 'Integration not found or unauthorized.'], 401);
        }

        $apiKey       = $integration->mc_token;
        $toolUrlJson  = json_decode($integration->tool->url, true);
        $base_api_url = $toolUrlJson['base_api_url'] ?? null;  
        if (in_array($toolName, [ToolNameEnum::ACTIVECAMPAIGN])) {
            $metadata     = json_decode($integration->metadata, true);
            $base_api_url = $metadata['api_url'] ?? $base_api_url; // Use API URL from metadata if available
            if (empty($base_api_url)) {
                return response()->json([
                    'message' => "{$integration->tool->name} API URL not found in integration metadata. Please re-authenticate.",
                    'success' => false,
                    'error' => "{$integration->tool->name} API URL missing."
                ], 400);
            }
        }
        $config = $this->getIntegrationListConfig($toolName, $toolUrlJson);

        if (!$config) {
            return response()->json(['success' => false, 'error' => 'Unsupported tool for listing data.'], 400);
        }

        try {
            $client = new Client([
                'base_uri'    => $base_api_url,
                'http_errors' => false, // Handle errors manually
            ]);

            $requestOptions = $config['request_options']($apiKey);
            $response       = $client->get($config['list_endpoint'], $requestOptions);
            $statusCode     = $response->getStatusCode();
            $responseData   = json_decode($response->getBody()->getContents(), true); 
            Log::info("{$toolName} fetch listing Response: " . json_encode($responseData));
            if ($config['success_check']($responseData)) { 
                $lists = $config['extract_lists']($responseData); 
                return response()->json([
                    'success' => true,
                    'message' => "{$toolName} lists fetched successfully.",
                    'data'    => $lists
                ], 200);
            } else {
                $errorMessage = $config['error_message_extractor']($responseData);
                return response()->json(['success' => false, 'error' => $errorMessage], $statusCode);
            }

        } catch (ClientException $e) { 
            $responseBody = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : 'No response body';
            Log::error("{$toolName} fetch listing ClientException: " . $e->getMessage() . " Response: " . $responseBody);
            $errorMessage = json_decode($responseBody, true)['Error']['Message'] ?? $e->getMessage();
            return response()->json(['success' => false, 'error' => "API call failed: " . $errorMessage], $e->getCode());
        } catch (\Exception $e) { 
            Log::error("Error in fetchApiKeyBasedListing for {$toolName}: " . $e->getMessage() . " Stack: " . $e->getTraceAsString());
            return response()->json(['success' => false, 'error' => 'An unexpected error occurred while fetching lists.'], 500);
        }
    }
 
    public function fetchMoosendListSubscribers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'integeration_id' => 'required|integer',
            'list_id'         => 'required|string',
            'toolName'        => 'required|string',
            'userId'          => 'required|string',
            'mc_dc'          => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'success' => false,
                'error' => $validator->errors()->first()
            ], 400);
        }

        $integeration_id = $request->input('integeration_id');
        $listId          = $request->input('list_id');
        $loggedInUserId  = Auth::user()->id;
        $toolName        = strtolower($request->input('toolName'));
        $mc_user_id      = $request->input('userId'); 
        $mc_dc           = $request->input('mc_dc'); 

        $integration = Integration::with('tool')->where('id', $integeration_id)->where('user_id', $loggedInUserId)->where('service_name',$toolName)->whereNull('deleted_at')->first();
        if (!$integration) {
            return response()->json(['success' => false, 'message' => 'Moosend integration not found or unauthorized.','error'=>'integration not found'], 401);
        }

        $apiKey       = $integration->mc_token;
        $toolUrlJson  = json_decode($integration->tool->url, true);
        $base_api_url = $toolUrlJson['base_api_url'] ?? null; // Default from tool config
        if (in_array($toolName,  [ToolNameEnum::ACTIVECAMPAIGN])) {
            $metadata = json_decode($integration->metadata, true);
            $base_api_url = $metadata['api_url'] ?? $base_api_url; // Use API URL from metadata if available
            if (empty($base_api_url)) {
                return response()->json([
                    'message' => "{$integration->tool->name} API URL not found in integration metadata. Please re-authenticate.",
                    'success' => false,
                    'error' => "{$integration->tool->name} API URL missing."
                ], 400);
            }
        }

        try {
            DB::beginTransaction(); 
            // Get the configuration for fetching subscribers using the new helper method
            $subscriberConfig = $this->getSubscriberConfig($toolName, $toolUrlJson);
            if (!$subscriberConfig) {
                 DB::rollBack(); // Rollback on config error
                return response()->json(['success' => false,'message'=>'Unsupported tool configuration for fetching subscribers.', 'error' => 'Unsupported tool configuration for fetching subscribers.'], 400);
            }

            $client = new Client([
                'base_uri'    => $base_api_url,
                'http_errors' => false, // Handle errors manually
            ]);

            $endpoint       = $subscriberConfig['subscriber_endpoint']($listId);
            $requestOptions = $subscriberConfig['request_options']($apiKey);

            $response     = $client->get($endpoint, $requestOptions);
            $statusCode   = $response->getStatusCode();
            $rawResponseBody = $response->getBody()->getContents(); // Get raw body
            $responseData = json_decode($rawResponseBody, true); // Decode to array
            Log::info("Moosend fetchListSubscribers Response for list $listId: " . json_encode($responseData)); 
            if ($subscriberConfig['success_check']($responseData)) {
                $emails = $subscriberConfig['extract_subscribers']($responseData);
                if (empty($emails)) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'No valid emails found from the connected account.',
                        'success' => false,
                        'error' => 'No emails available or an issue occurred during fetching.'
                    ], 404);
                }
                // Save emails to database
                $upload                            = new uploadedAndDownloadFileName();
                $upload->fileName                  = $toolName.' Import - ' . now()->format('Ymd_His');
                $upload->list_id                   = $listId;
                $upload->user_id                   = $loggedInUserId;
                $upload->tool_name                 = $integration->tool->name; // Use tool's actual name
                $upload->tool_id                   = $integration->tool->id;
                $upload->integeration_id           = $integeration_id;
                $upload->mc_user_id                = $mc_user_id; // Use mc_user_id from request
                $upload->mc_dc                     = $mc_dc; // Use mc_dc from request
                $upload->mc_token                  = $apiKey; // Store the API key
                $upload->is_tools_integerate_email = '1';
                $upload->uploadedFileLocation      = NULL; // Assuming no file upload for API import
                $upload->downloadFileName          = NULL;
                $upload->downloadFileLocation      = NULL;
                $upload->created_at                = now();
                $upload->updated_at                = now();
                $upload->save(); 
                
                $uploadId      = $upload->id;
                $importedCount = 0;

                foreach ($emails as $email) {
                    $record                            = new BulkUploadEmailFileData();
                    $record->email                     = $email; // Assuming extract_subscribers returns simple email strings
                    $record->file_id                   = $uploadId;
                    $record->importedBy                = $loggedInUserId;
                    $record->is_tools_integerate_email = '1';
                    $record->type                      = 'bulk';
                    $record->created_at                = now();
                    $record->updated_at                = now();
                    $record->save();
                    $importedCount++;
                }
                
                DB::commit(); // Commit transaction on success
                return response()->json([
                    'success'        => true,
                    'message'        => "Successfully imported {$importedCount} subscriber(s) from list '{$listId}'.",
                    'data'           => $emails, // Return the list of emails for confirmation
                    'imported_count' => $importedCount
                ], 200);
            } elseif ($statusCode === 404) {
                DB::rollBack(); // Rollback on 404
                return response()->json(['success' => false, 'message' => 'List not found or invalid List ID.','error' => 'List not found or invalid List ID.'], 404);
            } else {
                DB::rollBack(); // Rollback on other API errors
                $errorMessage = $subscriberConfig['error_message_extractor']($responseData);
                return response()->json(['success' => false,'message'=>'something went wrong', 'error' => $errorMessage], $statusCode);
            }

        } catch (ClientException $e) {
            DB::rollBack(); // Rollback on Guzzle ClientException
            $responseBody = $e->getResponse()->getBody()->getContents();
            Log::error("Moosend fetchListSubscribers ClientException for list $listId: " . $e->getMessage() . " Response: " . $responseBody);
            $errorMessage = json_decode($responseBody, true)['Error']['Message'] ?? $e->getMessage();
            return response()->json(['success' => false,'message'=>'API call failed', 'error' => "API call failed: " . $errorMessage], $e->getCode());
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on any other unexpected exception
            Log::error("Error in fetchMoosendListSubscribers for list $listId: " . $e->getMessage() . " Stack: " . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred while fetching Moosend subscribers.','error'=>'An unexpected error occurred while fetching Moosend subscribers.'], 500);
        }
    }

    private function getIntegrationListConfig($toolSlug, $urlJson)
    {
        switch ($toolSlug) {
            case ToolNameEnum::MOOSEND:
                return [
                    'list_endpoint' => 'lists.json',
                    'request_options' => function($apiKey) {
                        return ['query' => ['apikey' => $apiKey]];
                    },
                    'success_check' => function($responseData) {
                        return isset($responseData['Code']) && $responseData['Code'] === 0 && isset($responseData['Context']['MailingLists']);
                    },
                    'extract_lists' => function($responseData) {
                        return $responseData['Context']['MailingLists'] ?? [];
                    },
                    'error_message_extractor' => function($responseData) {
                        return $responseData['Error']['Message'] ?? 'Failed to fetch Moosend lists.';
                    }
                ];
            case ToolNameEnum::GETRESPONSE:
                return [
                    'list_endpoint' => 'campaigns', // GetResponse uses 'campaigns' as lists
                    'request_options' => function($apiKey) {
                        return ['headers' => ['X-Auth-Token' => "api-key $apiKey"]];
                    },
                    'success_check' => function($responseData) {
                        return is_array($responseData) && !isset($responseData['errorCode']); // Check if it's an array and no error code
                    },
                    'extract_lists' => function($responseData) {
                        // Map GetResponse campaigns to a common list format
                        return array_map(function($campaign) {
                            return [
                                'ID' => $campaign['campaignId'],
                                'Name' => $campaign['name'],
                                'SubscribersCount' => $campaign['subscribers'] ?? 0, // GetResponse campaigns might not directly have subscriber count here, might need another API call
                            ];
                        }, $responseData);
                    },
                    'error_message_extractor' => function($responseData) {
                        return $responseData['message'] ?? 'Failed to fetch GetResponse campaigns.';
                    }
                ];
            case ToolNameEnum::ACTIVECAMPAIGN:
                return [
                    'list_endpoint' => '/api/3/lists', // Corrected endpoint for ActiveCampaign
                    'request_options' => function($apiKey) {
                        return ['headers' => ['Api-Token' => $apiKey]];
                    },
                    'success_check' => function($responseData) {
                        return is_array($responseData) && isset($responseData['lists']); // ActiveCampaign returns lists in a 'lists' array
                    },
                    'extract_lists' => function($responseData) {
                        return array_map(function($list) {
                            return [
                                'ID' => $list['id'],
                                'Name' => $list['name'],
                                'SubscribersCount' => $list['subscriber_count'] ?? 0, // ActiveCampaign lists have subscriber_count
                            ];
                        }, $responseData['lists'] ?? []);
                    },
                    'error_message_extractor' => function($responseData) {
                        return $responseData['errors'][0]['message'] ?? 'Failed to fetch ActiveCampaign lists.';
                    }
                ];
            case ToolNameEnum::BREVO:
                return [
                    'list_endpoint' => 'contacts', // Brevo lists are effectively all contacts or segments
                    'request_options' => function ($apiKey) {
                        return ['headers' => ['api-key' => $apiKey, 'Accept' => 'application/json']];
                    },
                    'success_check' => function ($responseData) {
                        // Brevo returns contacts in a 'contacts' array
                        return isset($responseData['contacts']) && is_array($responseData['contacts']);
                    },
                    'extract_lists' => function ($responseData) {
                        // For Brevo, we'll return a single "All Contacts" list and potentially segments
                        $lists = [
                            [
                                'ID' => 'all_contacts', // A placeholder ID for all contacts
                                'Name' => 'All Contacts',
                                'SubscribersCount' => $responseData['count'] ?? count($responseData['contacts']),
                            ]
                        ];
                        // You could also fetch and add segments here if needed
                        // Example: Fetching lists (folders) from Brevo
                        // $segmentsResponse = $client->get('contacts/folders', $requestOptions);
                        // $segmentsData = json_decode($segmentsResponse->getBody()->getContents(), true);
                        // if (isset($segmentsData['folders'])) {
                        //     foreach ($segmentsData['folders'] as $folder) {
                        //         $lists[] = [
                        //             'ID' => $folder['id'],
                        //             'Name' => $folder['name'],
                        //             'SubscribersCount' => $folder['contactsCount'] ?? 0,
                        //         ];
                        //     }
                        // }
                        return $lists;
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Failed to fetch Brevo contacts/lists.';
                    }
                ];
            case ToolNameEnum::MAILERLITE: // Added for MailerLite
                return [
                    'list_endpoint' => 'groups', // MailerLite uses 'groups' as lists
                    'request_options' => function ($apiKey) {
                         return ['headers' => ['X-MailerLite-ApiKey' => $apiKey, 'Accept' => 'application/json']];
                    },
                    'success_check' => function ($responseData) {
                        // MailerLite returns groups as a direct array
                        return is_array($responseData) && count($responseData) > 0 && !isset($responseData['error']);
                    },
                    'extract_lists' => function ($responseData) {
                        return array_map(function ($group) {
                            return [
                                'ID'               => (String)$group['id'], 
                                'Name'             => $group['name'],
                                'SubscribersCount' => $group['total'] ?? 0, // MailerLite groups have subscribers_count
                            ];
                        }, $responseData);
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['error']['message'] ?? 'Failed to fetch MailerLite groups.';
                    }
                ];
                case ToolNameEnum::CONVERTKIT: // Added for ConvertKit
                return [
                    'list_endpoint' => 'tags', // ConvertKit uses 'tags' as lists/segments
                    'request_options' => function ($apiKey) {
                        return ['query' => ['api_secret' => $apiKey]];
                    },
                    'success_check' => function ($responseData) {
                        // ConvertKit returns tags in a 'tags' array
                        return isset($responseData['tags']) && is_array($responseData['tags']);
                    },
                    'extract_lists' => function ($responseData) {
                        return array_map(function ($tag) {
                            return [
                                'ID' => $tag['id'],
                                'Name' => $tag['name'],
                                'SubscribersCount' => $tag['subscribers_count'] ?? 0, // ConvertKit tags have subscribers_count
                            ];
                        }, $responseData['tags'] ?? []);
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Failed to fetch ConvertKit tags.';
                    }
                ];
            case ToolNameEnum::CONVERTKIT: // Added for ConvertKit
                return [
                    'list_endpoint' => 'tags', // ConvertKit uses 'tags' as lists/segments
                    'request_options' => function ($apiKey) {
                        return ['query' => ['api_secret' => $apiKey]];
                    },
                    'success_check' => function ($responseData) {
                        // ConvertKit returns tags in a 'tags' array
                        return isset($responseData['tags']) && is_array($responseData['tags']);
                    },
                    'extract_lists' => function ($responseData) {
                        return array_map(function ($tag) {
                            return [
                                'ID' => $tag['id'],
                                'Name' => $tag['name'],
                                'SubscribersCount' => $tag['subscribers_count'] ?? 0, // ConvertKit tags have subscribers_count
                            ];
                        }, $responseData['tags'] ?? []);
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Failed to fetch ConvertKit tags.';
                    }
                ];
            case ToolNameEnum::BENCHMARK:  // Added for Benchmark
                return [
                    'list_endpoint' => 'Contact/', // Endpoint to get lists/groups
                    'request_options' => function ($apiKey) {
                        return ['headers' => ['AuthToken' => $apiKey, 'Accept' => 'application/json']];
                    },
                    'success_check' => function ($responseData) {
                        // Benchmark returns lists as a direct array
                        return is_array($responseData) && !isset($responseData['Message']);
                    },
                    'extract_lists' => function ($responseData) { 
                        return array_map(function ($list) {
                            return [
                                'ID'               => $list['ID'] ?? null,
                                'Name'             => $list['Name'] ?? 'Unnamed List',
                                'SubscribersCount' => $list['ContactCount'] ?? 0,
                            ];
                        }, $responseData['Response']['Data'] ?? []);
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['Message'] ?? 'Failed to fetch Benchmark lists.';
                    }
                ];
            case ToolNameEnum::GIST: // Added for Gist
                return [
                    'list_endpoint' => 'segments/?include_count=true', // Gist endpoint for segments (which function as lists)
                    'request_options' => function ($apiKey) {
                        return ['headers' => ['Authorization' => "Token {$apiKey}"]];
                    },
                    'success_check' => function ($responseData) {
                        // Gist returns segments in a 'segments' array
                        return isset($responseData['segments']) && is_array($responseData['segments']);
                    },
                    'extract_lists' => function ($responseData) {
                        return array_map(function ($segment) {
                            return [
                                'ID'               => $segment['id'],
                                'Name'             => $segment['name'],
                                'SubscribersCount' => $segment['count'] ?? 0, // Gist segments have contacts_count
                            ];
                        }, $responseData['segments'] ?? []);
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Failed to fetch Gist segments.';
                    }
                ];
            case ToolNameEnum::MAILGUN: // Added for Mailgun
                return [
                    'list_endpoint' => 'lists', // Mailgun endpoint for mailing lists
                    'request_options' => function ($apiKey) {
                        return ['auth' => ['api', $apiKey]]; // Mailgun uses Basic Auth
                    },
                    'success_check' => function ($responseData) {
                        return isset($responseData['items']) && is_array($responseData['items']);
                    },
                    'extract_lists' => function ($responseData) {
                        return array_map(function ($list) {
                            return [
                                'ID' => $list['address'], // Use address as ID for mailing lists
                                'Name' => $list['name'] ?? $list['address'],
                                'SubscribersCount' => $list['members_count'] ?? 0,
                            ];
                        }, $responseData['items'] ?? []);
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Failed to fetch Mailgun mailing lists.';
                    }
                ];
            
            
            
            default:
                return null;
        }
    }

    private function getSubscriberConfig($toolSlug, $urlJson)
    {
        switch ($toolSlug) {
            case ToolNameEnum::MOOSEND:
                return [
                    'subscriber_endpoint' => function($listId) {
                        return "lists/$listId/subscribers.json";
                    },
                    'request_options' => function($apiKey) {
                        // Moosend API for subscribers might need pagination. Defaulting to PageSize=1000.
                        return ['query' => ['apikey' => $apiKey, 'PageSize' => 1000]];
                    },
                    'success_check' => function($responseData) {
                        return isset($responseData['Code']) && $responseData['Code'] === 0 && isset($responseData['Context']['Subscribers']);
                    },
                    'extract_subscribers' => function($responseData) {
                        return array_column($responseData['Context']['Subscribers'] ?? [], 'Email');
                    },
                    'error_message_extractor' => function($responseData) {
                        return $responseData['Error']['Message'] ?? 'Failed to fetch Moosend subscribers.';
                    }
                ];
            case ToolNameEnum::GETRESPONSE:
                return [
                    'subscriber_endpoint' => function($campaignId) {
                        return "campaigns/$campaignId/contacts"; // GetResponse contacts for a campaign
                    },
                    'request_options' => function($apiKey) {
                        return ['headers' => ['X-Auth-Token' => "api-key $apiKey"]];
                    },
                    'success_check' => function($responseData) {
                        return is_array($responseData) && !isset($responseData['errorCode']);
                    },
                    'extract_subscribers' => function($responseData) {
                        return array_column($responseData ?? [], 'email');
                    },
                    'error_message_extractor' => function($responseData) {
                        return $responseData['message'] ?? 'Failed to fetch GetResponse subscribers.';
                    }
                ];
            case ToolNameEnum::ACTIVECAMPAIGN:
                return [
                    'subscriber_endpoint' => function($listId) {
                        // For ActiveCampaign, to get contacts for a specific list, you'd use:
                        // return "/api/3/lists/$listId/contacts";
                        // However, for general import, fetching all contacts is more common.
                        return "/api/3/contacts"; // Corrected endpoint for ActiveCampaign
                    },
                    'request_options' => function($apiKey) {
                        return ['headers' => ['Api-Token' => $apiKey]];
                    },
                    'success_check' => function($responseData) {
                        return is_array($responseData) && isset($responseData['contacts']);
                    },
                    'extract_subscribers' => function($responseData) {
                        return array_column($responseData['contacts'] ?? [], 'email');
                    },
                    'error_message_extractor' => function($responseData) {
                        return $responseData['errors'][0]['message'] ?? 'Failed to fetch ActiveCampaign contacts.';
                    }
                ];
            case ToolNameEnum::BREVO:
                return [
                    'subscriber_endpoint' => function ($listId) {
                        // Brevo API for contacts. If a listId (segmentId) is provided, filter by it.
                        // Otherwise, fetch all contacts.
                        if ($listId === 'all_contacts') {
                            return "contacts";
                        }
                        // If it's a specific list/folder ID (segment), you'd typically filter contacts by listId.
                        // Brevo does not have a direct endpoint like "lists/{id}/contacts".
                        // You'd fetch all contacts and then filter by 'listIds' property if available, or use a segment endpoint.
                        // For simplicity, we'll assume 'all_contacts' for now for general import.
                        // If you need segment-specific import, you'd adjust this logic.
                        return "contacts";
                    },
                    'request_options' => function ($apiKey) {
                        // Brevo API for contacts supports pagination.
                        // Defaulting to limit=500 (max) and offset.
                        return [
                            'headers' => ['api-key' => $apiKey, 'Accept' => 'application/json'],
                            'query' => [
                                'limit' => 500, // Max limit per request
                                'offset' => 0, // Will be handled by pagination loop
                            ]
                        ];
                    },
                    'success_check' => function ($responseData) {
                        return isset($responseData['contacts']) && is_array($responseData['contacts']);
                    },
                    'extract_subscribers' => function ($responseData) {
                        $emails = [];
                        foreach ($responseData['contacts'] as $contact) {
                            if (isset($contact['email'])) {
                                $emails[] = $contact['email'];
                            }
                        }
                        return $emails;
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Failed to fetch Brevo subscribers.';
                    }
                ];
            case ToolNameEnum::MAILERLITE: // Added for MailerLite
                return [
                    'subscriber_endpoint' => function ($listId) {
                        // MailerLite has a dedicated endpoint for subscribers within a group.
                        if ($listId === 'all_contacts') {
                            // Fetch all subscribers, potentially paginated
                            return "subscribers";
                        }
                        return "groups/{$listId}/subscribers";
                    },
                    'request_options' => function ($apiKey) {
                        return ['headers' => ['X-MailerLite-ApiKey' => $apiKey, 'Accept' => 'application/json']];
                    },
                    'success_check' => function ($responseData) { 
                        // MailerLite returns subscribers as a direct array
                        return is_array($responseData) && count($responseData) >= 0 && !isset($responseData['error']);
                    },
                    'extract_subscribers' => function ($responseData) {
                        return array_column($responseData, 'email');
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['error']['message'] ?? 'Failed to fetch MailerLite subscribers.';
                    }
                ];
            case ToolNameEnum::CONVERTKIT: // Added for ConvertKit
                return [
                    'subscriber_endpoint' => function ($tagId) {
                        // ConvertKit API for subscribers within a tag (form or sequence)
                        // If it's a tag, fetch subscribers by tag. If 'all_contacts', fetch all subscribers.
                        if ($tagId === 'all_contacts') {
                            return "subscribers"; // Endpoint to get all subscribers
                        }
                        return "tags/{$tagId}/subscriptions"; // Endpoint for subscribers in a specific tag
                    },
                    'request_options' => function ($apiKey) {
                        return [
                            'query' => ['api_secret' => $apiKey],
                            'headers' => ['Accept' => 'application/json']
                        ];
                    },
                    'success_check' => function ($responseData) {
                        // Check for the 'subscriptions' array in the response
                        return isset($responseData['subscriptions']) && is_array($responseData['subscriptions']);
                    },
                    'extract_subscribers' => function ($responseData) {
                        $emails = [];
                        // Iterate through the 'subscriptions' array
                        foreach (($responseData['subscriptions'] ?? []) as $subscription) {
                            // Extract email_address from the nested 'subscriber' object
                            if (isset($subscription['subscriber']['email_address'])) {
                                $emails[] = $subscription['subscriber']['email_address'];
                            }
                        }
                        return $emails;
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Failed to fetch ConvertKit subscribers.';
                    }
                ];
            case ToolNameEnum::BENCHMARK: // Added for Benchmark
                return [
                    'subscriber_endpoint' => function ($listId) {
                        // Benchmark Email API for subscribers within a list
                        return "/Contact/{$listId}/ContactDetails";
                    },
                    'request_options' => function ($apiKey) {
                        return ['headers' => ['AuthToken' => $apiKey, 'Accept' => 'application/json']];
                    },
                    'query_params'    => ['Filter' => 1],
                    'success_check' => function ($responseData) {
                        // Benchmark returns subscribers nested under 'Response' and 'Data'
                        return isset($responseData['Response']['Data']) && is_array($responseData['Response']['Data']) && !isset($responseData['Message']);
                    },
                    'extract_subscribers' => function ($responseData) {
                        // Access the 'Data' array within the 'Response' object
                        return array_column($responseData['Response']['Data'] ?? [], 'Email');
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['Message'] ?? 'Failed to fetch Benchmark subscribers.';
                    }
                ];

            case ToolNameEnum::GIST: // Added for Gist
                return [
                    'subscriber_endpoint' => function ($segmentId) {
                        // Gist API for contacts within a segment
                        if ($segmentId === 'all_contacts') {
                            return "contacts"; // Endpoint to get all contacts
                        }
                        return "contacts?segment_id={$segmentId}"; // Endpoint for contacts in a specific segment
                    },
                    'request_options' => function ($apiKey) {
                        return ['headers' => ['Authorization' => "Token {$apiKey}"]];
                    },
                    'success_check' => function ($responseData) {
                        // Gist returns contacts in a 'contacts' array
                        return isset($responseData['contacts']) && is_array($responseData['contacts']);
                    },
                    'extract_subscribers' => function ($responseData) {
                        return array_column($responseData['contacts'] ?? [], 'email');
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Failed to fetch Gist subscribers.';
                    }
                ]; 

            case ToolNameEnum::MAILGUN: // Added for Mailgun
                return [
                    'subscriber_endpoint' => function ($domain) {
                        // Mailgun API for members within a mailing list (domain)
                        return "lists/$domain/members";
                    },
                    'request_options' => function ($apiKey) {
                        return ['auth' => ['api', $apiKey]]; // Mailgun uses Basic Auth
                    },
                    'success_check' => function ($responseData) {
                        return isset($responseData['items']) && is_array($responseData['items']);
                    },
                    'extract_subscribers' => function ($responseData) {
                        return array_column($responseData['items'] ?? [], 'address');
                    },
                    'error_message_extractor' => function ($responseData) {
                        return $responseData['message'] ?? 'Failed to fetch Mailgun subscribers.';
                    }
                ];
            default:
                return null;
        }
    }

}
