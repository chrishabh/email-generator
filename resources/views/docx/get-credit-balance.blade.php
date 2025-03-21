@extends('layout.header2')

@push('title')
    <title>{{$title}}</title>
@endpush

@section('content')

    <div class="prose max-w-none">
        <x-page-name-component 
        pageName='Get credit balance <br> <div class="flex items-center space-x-2 mt-3">
            <span class="bg-green-600 text-white text-[9px] font-bold px-1 py-1 rounded-md">GET</span>
            <span class="text-[#384248] text-[11px]">https://bouncee.net/api/v1/creditInfo</span>
        </div>'/>

        <p class="text-gray-600 pb-6">
            This API endpoint allows you to check the available verification credits in your <strong>Bouncee</strong> account.
        </p>

        <h2 class=" text-gray-900 font-[500] text-lg pb-2">Success Response</h2>
        <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 200 OK</p>
<pre class=" mt-2 text-[#b35e14]">{
"success":true,
"code":200,
 "credits_info": {
    "credits_remaining": 100
  }
}
</pre>
</div>'
    />




        <h2 class=" text-gray-900 font-[500] text-lg pb-2">Response parameter</h2>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Parameter</th>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Type</th> 
                        <th class="p-3 text-left border border-gray-300 font-semibold">Description</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">credits_remaining</td>
                        <td class="p-3 border border-gray-300">[integer]</td> 
                        <td class="p-3 border border-gray-300">The number of verification credits available in your account.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 class=" text-gray-900 font-[500] text-lg pb-2">Other Response</h2>
        <p class="text-gray-600 pb-6">
            Invalid API Key
        </p>
        <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 401 Unauthorized</p>
<pre class=" mt-2 text-[#b35e14]">{
"success":"false",
"code":401,
"result":"Invalid API key"
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
            </tbody>
        </table>
    </div>


    </div>

@endsection