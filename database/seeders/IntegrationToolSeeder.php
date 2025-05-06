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
            ['icon' => 'mailchimp.svg', 'name' => 'HubSpot', 'slug' => 'hubspot'],
            ['icon' => 'mailchimp.svg', 'name' => 'Zoho CRM', 'slug' => 'zoho'],
            ['icon' => 'mailchimp.svg', 'name' => 'Slack', 'slug' => 'slack'],
            ['icon' => 'mailchimp.svg', 'name' => 'Trello', 'slug' => 'trello'],
            ['icon' => 'mailchimp.svg', 'name' => 'ClickUp', 'slug' => 'clickup'],
            ['icon' => 'mailchimp.svg', 'name' => 'Salesforce', 'slug' => 'salesforce'],
            ['icon' => 'mailchimp.svg', 'name' => 'Google Sheets', 'slug' => 'google-sheets'],
            ['icon' => 'mailchimp.svg', 'name' => 'Asana', 'slug' => 'asana'],
            ['icon' => 'mailchimp.svg', 'name' => 'Basecamp', 'slug' => 'basecamp'],
        ];

        foreach ($tools as $tool) {
            IntegrationTool::updateOrCreate(['name' => $tool['name']], $tool);
        }
    }
}
