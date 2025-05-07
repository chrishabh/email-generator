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
            ['icon' => 'mailchimp.svg', 'name' => 'Mailchimp', 'slug' => 'mailchimp'],
            ['icon' => 'hubspot.png', 'name' => 'HubSpot', 'slug' => 'hubspot'],
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
