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
            ['icon' => 'mailchimp.svg', 'name' => 'Mailchimp', 'slug' => 'mailchimp','client_id'=>'341572595287','client_secret'=>'ac45c64ef6402e49b1b229772020b1b31036f3c7f332140ede','url'=>json_encode(['redirect_url'=>'http://127.0.0.1:8000/mailchimp/callback','auth_login_url'=>'https://login.mailchimp.com/oauth2/authorize','auth_token_url'=>'https://login.mailchimp.com/oauth2/token','auth_metadata_url'=>'https://login.mailchimp.com/oauth2/metadata','base_api_url'=>'https://.api.mailchimp.com/3.0/'])],
            ['icon' => 'google-analytics.svg', 'name' => 'Google Analytics', 'slug' => 'google analytics'],
            ['icon' => 'google-ads.svg', 'name' => 'Google Ads', 'slug' => 'google ads'],
            ['icon' => 'facebook-ads.svg', 'name' => 'Facebook Ads', 'slug' => 'facebook ads'],
            ['icon' => 'google-search-console.svg', 'name' => 'Google Search Console', 'slug' => 'google search console'],
            ['icon' => 'google-tag-manager.svg', 'name' => 'Google Tag Manager', 'slug' => 'google tag manager'],
            ['icon' => 'google-drive.svg', 'name' => 'Google Drive', 'slug' => 'google drive'],
            ['icon' => 'slack.svg', 'name' => 'Slack', 'slug' => 'slack'],
            ['icon' => 'asana.png', 'name' => 'Asana', 'slug' => 'asana'],
            ['icon' => 'trello.png', 'name' => 'Trello', 'slug' => 'trello'],
            ['icon' => 'jira.png', 'name' => 'Jira', 'slug' => 'jira'],
            ['icon' => 'salesforce.svg', 'name' => 'Salesforce', 'slug' => 'salesforce'],
            ['icon' => 'pipedrive.svg', 'name' => 'Pipedrive', 'slug' => 'pipedrive'],
            ['icon' => 'freshdesk.png', 'name' => 'Freshdesk', 'slug' => 'freshdesk'],
            ['icon' => 'freshsales.png', 'name' => 'Freshsales', 'slug' => 'freshsales'],
            ['icon' => 'freshservice.png', 'name' => 'Freshservice', 'slug' => 'freshservice'],
            ['icon' => 'klaviyo.png', 'name' => 'Klaviyo', 'slug' => 'klaviyo'],
            ['icon' => 'hubspot.png', 'name' => 'HubSpot', 'slug' => 'hubspot','client_id'=>'dca0fd66-10cd-4630-b904-b798d62a8bcb','client_secret'=>'ed770db5-6c85-4b44-a6ca-dec057d6f4bf','url'=>json_encode(['redirect_url'=> (strtolower(env('APP_ENV')) === 'production') ? 'https://bouncee.net/hubspot/callback' :'http://localhost:8000/hubspot/callback','auth_login_url'=>'https://app-na2.hubspot.com/oauth/authorize','auth_token_url'=>'https://api.hubapi.com/oauth/v1/token','auth_metadata_url'=>'https://api.hubapi.com/integrations/v1/me','base_api_url'=>'https://api.hubapi.com/'])],
            ['icon' => 'microsoft-teams.svg', 'name' => 'Microsoft Teams', 'slug' => 'microsoft teams'],
            ['icon' => 'microsoft-outlook.svg', 'name' => 'Microsoft Outlook', 'slug' => 'microsoft outlook'],
            ['icon' => 'microsoft-onedrive.svg', 'name' => 'Microsoft OneDrive', 'slug' => 'microsoft onedrive'],
            ['icon' => 'microsoft-sharepoint.svg', 'name' => 'Microsoft SharePoint', 'slug' => 'microsoft sharepoint'],
            ['icon' => 'microsoft-excel.svg', 'name' => 'Microsoft Excel', 'slug' => 'microsoft excel'],
            ['icon' => 'microsoft-word.svg', 'name' => 'Microsoft Word', 'slug' => 'microsoft word'],
            ['icon' => 'microsoft-powerpoint.svg', 'name' => 'Microsoft PowerPoint', 'slug' => 'microsoft powerpoint'],
            ['icon' => 'github.png', 'name' => 'GitHub', 'slug' => 'github'],
            ['icon' => 'gitlab.png', 'name' => 'GitLab', 'slug' => 'gitlab'],
            ['icon' => 'bitbucket.png', 'name' => 'Bitbucket', 'slug' => 'bitbucket'],
            ['icon' => 'wordpress.png', 'name' => 'WordPress', 'slug' => 'wordpress'],
            ['icon' => 'woocommerce.png', 'name' => 'WooCommerce', 'slug' => 'woocommerce'],
            ['icon' => 'drip.svg', 'name' => 'Drip', 'slug' => 'drip'],
            ['icon' => 'google_sheet.svg', 'name' => 'Google Sheets', 'slug' => 'google sheets'],
            ['icon' => 'zoho-crm.svg', 'name' => 'Zoho Crm', 'slug' => 'zoho crm'],
            ['icon' => 'aweber.png', 'name' => 'AWeber', 'slug' => 'aweber'],
            ['icon' => 'dropbox.svg', 'name' => 'Dropbox', 'slug' => 'dropbox'],
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
