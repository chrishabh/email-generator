@extends('layout.header2')

@push('title')
    <title>{{$title}}</title>
@endpush

@section('content')

    <div class="prose max-w-none">
        <x-page-name-component 
        pageName='Single Validation API <br> <div class="flex items-center space-x-2 mt-3">
            <span class="bg-green-600 text-white text-[9px] font-bold px-1 py-1 rounded-md">GET</span>
            <span class="text-[#384248] text-[11px]">https://bouncee.net/api/v1/verify</span>
        </div>'/>

        <p class="text-gray-600 pb-6">
            The <strong>Bouncee</strong> Single Email Validation API allows you to verify one email address per request. This endpoint is ideal for validating individual email addresses in real-time.
        </p>



        <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 200 OK</p>
<pre class=" mt-2 text-[#b35e14]">{
"status": "deliverable",
"success":true,
"code":200,
"email": "support@bouncee.com",
"domain": "bouncee.com",
"user": "support"
}
</pre>
</div>'
    />




        <x-warning-text-component 
            text='<ul class="mt-2 list-disc pl-6 ">
                    <li><strong>Deliverable- </strong> The email address is valid and can receive emails.</li>
                    <li><strong>Accept-All & Unknown- </strong> The email address could not be fully verified. It may still be valid, so do not reject these emails automatically.</li>
                    <li><strong>Undeliverable- </strong> The email address is invalid and should be rejected.</li>
                    <li>If the request was unsuccessful, check the response message before proceeding with your workflow.</li>
                </ul>'
        />

        <h2 class=" text-gray-900 font-[500] text-lg pb-2">Response Parameters</h2>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Parameter</th>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Type</th>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Definition</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">status</td>
                        <td class="p-3 border border-gray-300">[string]</td>
                        <td class="p-3 border border-gray-300">Email verification result (deliverable, undeliverable, unknown, accept_all).</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">success</td>
                        <td class="p-3 border border-gray-300">[boolean]</td>
                        <td class="p-3 border border-gray-300">Email verification success (false, true).</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="p-3 border border-gray-300">code</td>
                        <td class="p-3 border border-gray-300">[number]</td>
                        <td class="p-3 border border-gray-300">A numeric status indicating the API result.</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">email</td>
                        <td class="p-3 border border-gray-300">[email]</td>
                        <td class="p-3 border border-gray-300">The email address that was verified.</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td class="p-3 border border-gray-300">user</td>
                        <td class="p-3 border border-gray-300">[string]</td>
                        <td class="p-3 border border-gray-300">Containing authenticate user name </td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">domain</td>
                        <td class="p-3 border border-gray-300">[string]</td>
                        <td class="p-3 border border-gray-300">The domain part of the email address. </td>
                    </tr>
                </tbody>
            </table>
        </div>



        
        <h2 class=" text-gray-900 font-[500] text-lg pb-2">Other Responses</h2>
        <p class="text-gray-600 pb-6">
            Invalid or Missing Email Address:
        </p>

        <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{
"result": "Both Email and api key is required."
"success":false,
"code":400,
}
</pre>
</div>'
/>


    <p class="text-gray-600 pb-6">
        Invalid API Key:
    </p>
    <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 401 Unauthorized</p>
<pre class=" mt-2 text-[#b35e14]">{
"success":false,
"code":401,
"result": "Invalid API Key"
}
</pre>
</div>'
/>




    <p class="text-gray-600 pb-6">
        Insufficient Verification Credits:
    </p>
<x-api-view-component
text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 402 Payment Required</p>
<pre class=" mt-2 text-[#b35e14]">{
"success":false,
"code":402,
"result": "Insufficient verification credits"
}
</pre>
</div>'
/>


    <p class="text-gray-600 pb-6">
        Rate Limit Exceeded (More Than 120 Requests Per Minute):
    </p>
    <x-api-view-component
text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1  429 Too Many Requests</p>
<pre class=" mt-2 text-[#b35e14]">{
"success":false,
"code":429,
"result": "Too many requests"
}
</pre>
</div>'
/>


<h2 class=" text-gray-900 font-[500] text-lg pb-2">QUERY PARAMS</h2>
<div class="overflow-x-auto">
    <table class="w-full border border-gray-300 rounded-lg">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left border border-gray-300 font-semibold">Parameter</th>
                <th class="p-3 text-left border border-gray-300 font-semibold">Type</th>
                <th class="p-3 text-left border border-gray-300 font-semibold">Required</th>
                <th class="p-3 text-left border border-gray-300 font-semibold">Description</th>
                
            </tr>
        </thead>
        <tbody>
            <tr class="border border-gray-300">
                <td class="p-3 border border-gray-300">apikey</td>
                <td class="p-3 border border-gray-300">[string]</td>
                <td class="p-3 border border-gray-300"><strong>Yes</strong></td>
                <td class="p-3 border border-gray-300">Your <strong>Bouncee</strong> API key.</td>
            </tr>
            <tr class="border border-gray-300">
                <td class="p-3 border border-gray-300">email</td>
                <td class="p-3 border border-gray-300">[string]</td>
                <td class="p-3 border border-gray-300"><strong>Yes</strong></td>
                <td class="p-3 border border-gray-300"> The email address to be verified.</td>
            </tr> 
        </tbody>
    </table>
</div>
    </div>
@endsection