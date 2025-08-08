<?php

namespace Database\Seeders;

use App\Models\IntegrationTool;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntegrationToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tools = [ 
            ['icon' => 'mailchimp.svg', 'name' => 'Mailchimp', 'slug' => 'mailchimp','client_id'=>'341572595287','client_secret'=>'ac45c64ef6402e49b1b229772020b1b31036f3c7f332140ede','url'=>json_encode(['redirect_url'=>'https://bouncee.net/mailchimp/callback','auth_login_url'=>'https://login.mailchimp.com/oauth2/authorize','auth_token_url'=>'https://login.mailchimp.com/oauth2/token','auth_metadata_url'=>'https://login.mailchimp.com/oauth2/metadata','base_api_url'=>'https://.api.mailchimp.com/3.0/'])],
            ['icon' => 'hubspot.png', 'name' => 'HubSpot', 'slug' => 'hubspot','client_id'=>'dca0fd66-10cd-4630-b904-b798d62a8bcb','client_secret'=>'ed770db5-6c85-4b44-a6ca-dec057d6f4bf','url'=>json_encode(['redirect_url'=> (strtolower(env('APP_ENV')) === 'Production') ? 'https://bouncee.net/hubspot/callback' :'http://localhost:8000/hubspot/callback','auth_login_url'=>'https://app-na2.hubspot.com/oauth/authorize','auth_token_url'=>'https://api.hubapi.com/oauth/v1/token','auth_metadata_url'=>'https://api.hubapi.com/integrations/v1/me','base_api_url'=>'https://api.hubapi.com/'])],       
            ['icon' => 'google_sheet.svg', 'name' => 'Google Sheets', 'slug' => 'google sheets','client_id'=>env('GOOGLE_CLIENT_ID'),'client_secret'=>env('GOOGLE_CLIENT_SECRET'),'url'=>json_encode(['redirect_url'=> (strtolower(env('APP_ENV')) === 'Production') ? 'https://bouncee.net/googlesheet/callback' :'https://bouncee.net/googlesheet/callback','auth_login_url'=>'https://accounts.google.com/o/oauth2/auth','auth_token_url'=>'https://oauth2.googleapis.com/token',"auth_metadata_url"=>"https://www.googleapis.com/oauth2/v3/userinfo",'base_api_url'=>'https://sheets.googleapis.com/v4/'])],
            ['icon' => 'dropbox.svg', 'name' => 'Dropbox', 'slug' => 'dropbox','client_id'=>env('DROPBOX_CLIENT_ID'),'client_secret'=>env('DROPBOX_CLIENT_SECRET'),'url'=>json_encode(['redirect_url'=> ((env('APP_ENV')) === 'Production') ? 'https://bouncee.net/dropbox/callback' :'http://localhost:8000/dropbox/callback','auth_login_url'=>'https://www.dropbox.com/oauth2/authorize','auth_token_url'=>'https://api.dropboxapi.com/oauth2/token','auth_metadata_url'=>'users/get_current_account','base_api_url'=>'https://api.dropboxapi.com/2/'])],   
            ['icon' => 'drip.svg', 'name' => 'Drip', 'slug' => 'drip', 'client_id' => env('DRIP_CLIENT_ID'), 'client_secret' => env('DRIP_CLIENT_SECRET'), 'url' => json_encode(['redirect_url' =>  ((env('APP_ENV')) === 'Production') ?'https://bouncee.net/drip/callback':'http://localhost:8000/drip/callback','auth_login_url' => 'https://www.getdrip.com/oauth/authorize','auth_token_url' => 'https://www.getdrip.com/oauth/token','auth_metadata_url' => 'https://api.getdrip.com/v2/accounts', 'base_api_url' => 'https://api.getdrip.com/v2/'])],
            ['icon' => 'constant_contact.svg', 'name' => 'Constant Contact', 'slug' => 'constant contact', 'client_id' => env('CONSTANT_CONTACT_CLIENT_ID'), 'client_secret' => env('CONSTANT_CONTACT_SECRET_KEY'), 'url' => json_encode(['redirect_url' => (strtolower(env('APP_ENV')) === 'Production') ? 'https://bouncee.net/constantcontact/callback' : 'https://bouncee.net/constantcontact/callback','auth_login_url' => 'https://authz.constantcontact.com/oauth2/default/v1/authorize','auth_token_url' => 'https://authz.constantcontact.com/oauth2/default/v1/token','auth_metadata_url' => 'https://api.cc.email/v3/account/summary', 'base_api_url' => 'https://api.cc.email/v3/'])],
            ['icon' => 'aweber.png', 'name' => 'AWeber', 'slug' => 'aweber','client_id' => env('AWEBER_CLIENT_ID'), 'client_secret' => env('AWEBER_CLIENT_SECRET'),'url' => json_encode(['redirect_url' => (strtolower(env('APP_ENV')) === 'Production') ? 'https://bouncee.net/aweber/callback' : 'https://bouncee.net/aweber/callback','auth_login_url' => 'https://auth.aweber.com/oauth2/authorize','auth_token_url' => 'https://auth.aweber.com/oauth2/token','auth_metadata_url' => 'https://api.aweber.com/1.0/accounts','base_api_url' => 'https://api.aweber.com/1.0/'])],     
            ['icon' => 'webengage.png', 'name' => 'Web Engage', 'slug' => 'web engage','url' => json_encode(['base_api_url' => 'https://api.webengage.com/v1/'])], 
            ['icon' => 'campaign-monitor.png', 'name' => 'Campaign Monitor', 'slug' => 'campaign monitor', 'client_id' => env('CAMPAIGN_MONITOR_CLIENT_ID'), 'client_secret' => env('CAMPAIGN_MONITOR_CLIENT_SECRET'), 'url' => json_encode(['redirect_url' => (strtolower(env('APP_ENV')) === 'Production') ? 'https://bouncee.net/campaignmonitor/callback' : 'https://bouncee.net/campaignmonitor/callback', 'auth_login_url' => 'https://api.createsend.com/oauth', 'auth_token_url' => 'https://api.createsend.com/oauth/token',  'auth_metadata_url' => null,'base_api_url' => 'https://api.createsend.com/api/v3.3/'])],
            ['icon' => 'active_campaign.svg', 'name' => 'Active Campaign', 'slug' => 'active campaign'],
            ['icon' => 'getresponse.png', 'name' => 'Get Response', 'slug' => 'get response', 'url' => json_encode(['base_api_url' => 'https://api.getresponse.com/v3/'])],
            ['icon' => 'brevo.png', 'name' => 'Brevo', 'slug' => 'brevo' ,'url' => json_encode(['base_api_url' => 'https://api.brevo.com/v3/'])],
            ['icon' => 'mailgun.png', 'name' => 'Mailgun', 'slug' => 'mailgun','url' => json_encode(['base_api_url' => 'https://api.mailgun.net/v3/'])],
            ['icon' => 'moosend.png', 'name' => 'Moosend', 'slug' => 'moosend','url' => json_encode(['base_api_url' => 'https://api.moosend.com/v3/'])],
            ['icon' => 'zohocampaign.png', 'name' => 'Zoho Campaign', 'slug' => 'zoho campaign', 'client_id' => env('ZOHO_CAMPAIGN_CLIENT_ID'), 'client_secret' => env('ZOHO_CAMPAIGN_CLIENT_SECRET'), 'url' => json_encode(['redirect_url' => (strtolower(env('APP_ENV')) === 'Production') ? 'https://bouncee.net/zoho/callback':'https://bouncee.net/zoho/callback','auth_login_url' => 'https://accounts.zoho.in/oauth/v2/auth','auth_token_url' => 'https://accounts.zoho.in/oauth/v2/token','auth_metadata_url' => 'https://accounts.zoho.in/oauth/user/info','base_api_url' => 'https://campaigns.zoho.in/api/v1.1/'])],
            ['icon' => 'mailerlite.png', 'name' => 'Mailer Lite', 'slug' => 'mailer lite','url' => json_encode(['base_api_url' => 'https://api.mailerlite.com/api/v2/'])],
            ['icon' => 'mailjet.svg', 'name' => 'Mailjet', 'slug' => 'mailjet','url' => json_encode(['base_api_url' => 'https://api.mailjet.com/v3/REST/'])],
            ['icon' => 'gist.png', 'name' => 'Gist', 'slug' => 'gist', 'url' => json_encode(['base_api_url' => 'https://api.getgist.com/'])],
            ['icon' => 'convertkit.png', 'name' => 'ConvertKit', 'slug' => 'convertkit','url' => json_encode(['base_api_url' => 'https://api.convertkit.com/v3/'])],
            ['icon' => 'Benchmark.png', 'name' => 'Benchmark', 'slug' => 'benchmark','url' => json_encode(['base_api_url' => 'https://clientapi.benchmarkemail.com/'])],
            ['icon' => 'intercom.svg', 'name' => 'Intercom', 'slug' => 'intercom', 'client_id' => env('INTERCOM_CLIENT_ID'), 'client_secret' => env('INTERCOM_CLIENT_SECRET'), 'url' => json_encode(['redirect_url' => 'https://bouncee.net/intercom/callback', 'auth_login_url' => 'https://app.intercom.com/oauth', 'auth_token_url' => 'https://api.intercom.io/auth/eagle/token', 'auth_metadata_url' => 'https://api.intercom.io/me','base_api_url' => 'https://api.intercom.io/'])],
            ['icon' => 'zapier.png', 'name' => 'Zapier', 'slug' => 'zapier'],
            ['icon' => 'clay.png', 'name' => 'Clay', 'slug' => 'clay'],
        ];

        foreach ($tools as $tool) { 
             $existingTool = IntegrationTool::where('name', $tool['name'])->first(); 
            if ($existingTool) {
                // Update all columns
                $existingTool->update($tool);
            } else {
                // Create new if not exists
                IntegrationTool::create($tool);
            }
        }
    }
}
