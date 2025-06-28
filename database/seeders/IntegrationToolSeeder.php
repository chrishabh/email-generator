<?php

namespace Database\Seeders;

use App\Models\IntegrationTool;
use Illuminate\Database\Seeder;

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
            ['icon' => 'google_sheet.svg', 'name' => 'Google Sheets', 'slug' => 'google sheets','client_id'=>env('google_client_id'),'client_secret'=>env('google_client_secret'),'url'=>json_encode(['redirect_url'=> (strtolower(env('APP_ENV')) === 'Production') ? 'https://bouncee.net/googleSheets/callback' :'http://localhost:8000/googleSheets/callback','auth_login_url'=>'https://accounts.google.com/o/oauth2/auth','auth_token_url'=>'https://oauth2.googleapis.com/token',"auth_metadata_url"=>"https://www.googleapis.com/oauth2/v2/userinfo",'base_api_url'=>'https://sheets.googleapis.com/v4/spreadsheets/'])],
            ['icon' => 'dropbox.svg', 'name' => 'Dropbox', 'slug' => 'dropbox','client_id'=>'q7boqkgck0jkthl','client_secret'=>'uiorkk5laun8vcq','url'=>json_encode(['redirect_url'=> ((env('APP_ENV')) === 'Production') ? 'https://bouncee.net/hubspot/callback' :'http://localhost:8000/dropbox/callback','auth_login_url'=>'https://www.dropbox.com/oauth2/authorize','auth_token_url'=>'https://api.dropboxapi.com/oauth2/token','auth_metadata_url'=>'users/get_current_account','base_api_url'=>'https://api.dropboxapi.com/2/'])],   
            ['icon' => 'drip.svg', 'name' => 'Drip', 'slug' => 'drip'],
            ['icon' => 'zoho-crm.svg', 'name' => 'Zoho Crm', 'slug' => 'zoho crm','client_id'=>env('zoho_client_id'),'client_secret'=>env('zoho_client_secret'),'url'=>json_encode(['redirect_url'=> (strtolower(env('APP_ENV')) === 'Production') ? 'https://bouncee.net/zoho/callback' :'http://localhost:8000/zoho/callback','auth_login_url'=>'https://accounts.zoho.in/oauth/v2/auth','auth_token_url'=>'https://accounts.zoho.in/oauth/v2/token','base_api_url'=>'https://api.hubapi.com/'])],
            ['icon' => 'aweber.png', 'name' => 'AWeber', 'slug' => 'aweber'],            
            ['icon' => 'webengage.png', 'name' => 'Web Engage', 'slug' => 'web engage'],
            ['icon' => 'campaign-monitor.png', 'name' => 'Campaign Monitor', 'slug' => 'campaign monitor'],
            ['icon' => 'active_campaign.svg', 'name' => 'Active Campaign', 'slug' => 'active campaign'],
            ['icon' => 'getresponse.png', 'name' => 'Get Response', 'slug' => 'get response'],
            ['icon' => 'brevo.png', 'name' => 'Brevo', 'slug' => 'brevo'],
            ['icon' => 'mailgun.png', 'name' => 'Mailgun', 'slug' => 'mailgun'],
            ['icon' => 'moosend.png', 'name' => 'Mosend', 'slug' => 'mosend'],
            ['icon' => 'zohocampaign.png', 'name' => 'Zoho Campaign', 'slug' => 'zoho campaign'],
            ['icon' => 'mailerlite.png', 'name' => 'MainerLite', 'slug' => 'mailerlite'],
            ['icon' => 'mailjet.svg', 'name' => 'Mailjet', 'slug' => 'mailjet'],
            ['icon' => 'gist.png', 'name' => 'Gist', 'slug' => 'gist'],
            ['icon' => 'convertkit.png', 'name' => 'ConvertKit', 'slug' => 'convertkit'],
            ['icon' => 'Benchmark.png', 'name' => 'Benchmark', 'slug' => 'benchmark'],
            ['icon' => 'intercom.svg', 'name' => 'Intercom', 'slug' => 'intercom'],
            ['icon' => 'zapier.png', 'name' => 'Zapier', 'slug' => 'zapier'],
        ];

        foreach ($tools as $tool) {
            IntegrationTool::updateOrCreate(['name' => $tool['name']], $tool);
        }
    }
}
