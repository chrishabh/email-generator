<?php

namespace App\Http\Controllers\Integeration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class MailchimpOAuthController extends Controller
{
    public function redirectToMailchimp()
    {
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => env('MAILCHIMP_CLIENT_ID'),
            'redirect_uri' => env('MAILCHIMP_REDIRECT_URI'),
        ]);

        return redirect("https://login.mailchimp.com/oauth2/authorize?$query");
    }

    public function handleCallback(Request $request)
    {
        $code = $request->input('code');

        $client = new Client();
        $response = $client->post('https://login.mailchimp.com/oauth2/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => env('MAILCHIMP_CLIENT_ID'),
                'client_secret' => env('MAILCHIMP_CLIENT_SECRET'),
                'redirect_uri' => env('MAILCHIMP_REDIRECT_URI'),
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

        Session::put('mc_token', $accessToken);
        Session::put('mc_dc', $meta['dc']);
        Session::put('mc_user_id', $meta['user_id']);

        return redirect('/mailchimp/validate-emails');
    }



    public function validateEmails()
    {
        $accessToken = Session::get('mc_token');
        $dc = Session::get('mc_dc');

        $client = new Client([
            'base_uri' => "https://$dc.api.mailchimp.com/3.0/",
            'headers' => ['Authorization' => "OAuth $accessToken"]
        ]);

        // 1. Get Lists
        $lists = json_decode($client->get('lists')->getBody(), true);
        if (empty($lists['lists'])) return 'No lists found';

        $listId = $lists['lists'][0]['id']; // first list

        // 2. Get members
        $members = json_decode($client->get("lists/$listId/members")->getBody(), true);
        $emails = array_column($members['members'], 'email_address');

        // 3. Validate with Bouncify
        $results = [];
        $bouncify = new Client(['base_uri' => 'https://api.bouncify.io/v1/']);
        foreach ($emails as $email) {
            $res = $bouncify->get('email/verify', [
                'query' => ['email' => $email],
                'headers' => ['Authorization' => 'Bearer ' . env('BOUNCIFY_API_KEY')]
            ]);
            $data = json_decode($res->getBody(), true);
            $results[] = ['email' => $email, 'status' => $data['result']];
        }

        return view('mailchimp.results', compact('results'));
    }

}
