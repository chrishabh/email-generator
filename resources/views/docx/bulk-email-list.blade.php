@extends('layout.header2')

@push('title')
    <title>{{$title}}</title>
@endpush

@section('content')

    <div class="prose max-w-none">
        <x-page-name-component 
        pageName='Upload a bulk email list <br> <div class="flex items-center space-x-2 mt-3">
            <span class="bg-green-600 text-white text-[9px] font-bold px-1 py-1 rounded-md">POST</span>
            <span class="text-[#384248] text-[11px]">https://bouncee.net/api/v1/bulk?apiKey=x23yz</span>
        </div>'/>

        <p class="text-gray-600 pb-6">
            This endpoint enables you to upload a list of email addresses for bulk verification. You can either upload a .csv file or pass a JSON array of email data directly.<br>
            If auto_verify is set to true, the list is immediately processed, and the verification begins automatically. If it's set to false, the list is simply uploaded and prepared for later verification via a separate request. The response includes a unique job_id that can be used to track or retrieve the results later.
            <strong>Upload Options</strong><br>
            <strong>Option 1: CSV File Upload</strong>
            <p>
                •	Accepted format: .csv (Comma Separated Values)
                •	Max file size: 10 MB
                •	Max emails per file: 500,000
                •	Additional columns will be retained for reference
            </p>
             
        </p>


 



<x-api-view-component
text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 200 OK</p>
<pre class=" mt-2 text-[#b35e14]">{
  "job_id": "abcd1234xyz",
  "success": true,
  "message": "Bulk email list uploaded successfully"
}

</pre>

<p>If auto_verify is enabled:</p>

<pre class=" mt-2 text-[#b35e14]">{
  "job_id": "abcd1234xyz",
  "success": true,
  "message": "Bulk email list uploaded and verification initiated"
}

</pre>
</div>'
/>


<h2 class="text-gray-900 font-[500] text-lg pb-2">❌ Error Responses</h2>
<p class="text-gray-600 pb-6">
    Invalid API Key
</p>
<x-api-view-component
text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 401 Unauthorized</p>
<pre class=" mt-2 text-[#b35e14]">{
  "result": "Invalid API Key",
  "success": false
}

</pre>
</div>'
/>
<p class="text-gray-600 pb-6">
    Invalid File Format
</p>
<x-api-view-component
text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{
  "result": "Invalid file data",
  "success": false
}


</pre>
</div>'
/>
<p class="text-gray-600 pb-6">
    Maximum List Upload Limit Reached
</p>
<x-api-view-component
text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{
  "success": false,
  "message": "The maximum number of lists has been reached."
}

</pre>
</div>'
/>
<p class="text-gray-600 pb-6">
    Maximum Active Verifications Reached
</p>
<x-api-view-component
text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{
  "success": false,
  "message": "The maximum number of active verification lists has been reached."
}

</pre>
</div>'
/>

        <h2 class=" text-gray-900 font-[500] text-lg pb-2 pt-12">Query Parameters</h2>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Parameter</th>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Type</th>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Required</th>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Definition</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">apikey</td>
                        <td class="p-3 border border-gray-300">[string]</td>
                        <td class="p-3 border border-gray-300">Yes</td>
                        <td class="p-3 border border-gray-300">apikey	string	Yes	Your API key for access</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 class=" text-gray-900 font-[500] text-lg pb-2 pt-12">Body Parameters</h2>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Parameter</th>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Type</th>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Required</th>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Definition</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">local_file</td>
                        <td class="p-3 border border-gray-300">[file (.csv)]</td>
                        <td class="p-3 border border-gray-300">Yes</td>
                        <td class="p-3 border border-gray-300">The file to upload</td>
                    </tr>
                </tbody>
            </table>
        </div>


            {{-- next prev page --}} 
    <x-back-next-button-component 
    nextPageName="Start verifying bulk email list" 
    nextUrl="{{ route('reference.page', ['page' => 'start-verifying-bulk-email-list']) }}" 
    prevUrl="{{ route('reference.page', ['page' => 'single-validation-api']) }}" 
    prevPageName="Single Validation API"
    />
    </div>
@endsection