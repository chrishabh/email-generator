@extends('layout.header2')

@push('title')
    <title>{{$title}}</title>
@endpush

@section('content')

    <div class="prose max-w-none">
        <x-page-name-component 
        pageName='Start Bulk Email List Verification <br> <div class="flex items-center space-x-2 mt-3">
            <span class="bg-green-600 text-white text-[9px] font-bold px-1 py-1 rounded-md">PATCH</span>
            <span class="text-[#384248] text-[11px]">https://api.bouncee.net/v1/bulk</span>
        </div>'/>

        <p class="text-gray-600 pb-6">
            Use this endpoint to initiate the verification process for an email list you've previously uploaded. The list must have a status of <strong>ready</strong>, and must not have been automatically verified during the upload process (auto_verify = false). Once initiated, the list begins verification and results will be available upon completion.
        </p>


        <h2 class="text-gray-900 font-[500] text-lg pb-2">Successful Response</h2>
        <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 200 OK</p>
<pre class=" mt-2 text-[#b35e14]">{
    "job_id": "abc123xyz456",
    "success": true,
    "message": "Verification will begin shortly. Use the /status endpoint to monitor progress."
}


</pre>
</div>'
    />




    <x-warning-text-component 
    text="•	A list can only be verified once.<br>
•	You cannot pause, cancel, or restart a verification once it begins.<br>
•	Make sure the list is in the ready state before triggering verification.

"
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
                        <td class="p-3 border border-gray-300">Your API key for access</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 class=" text-gray-900 font-[500] text-lg pb-2 pt-12">Path Parameters</h2>
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
                        <td class="p-3 border border-gray-300">job_id</td>
                        <td class="p-3 border border-gray-300">[string]</td>
                        <td class="p-3 border border-gray-300">Yes</td>
                        <td class="p-3 border border-gray-300">ID of the job you want to verify</td>
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
                        <td class="p-3 border border-gray-300">action</td>
                        <td class="p-3 border border-gray-300">[string]</td>
                        <td class="p-3 border border-gray-300">Yes</td>
                        <td class="p-3 border border-gray-300">Always set to "start"</td>
                    </tr>
                     
                </tbody>
            </table>
        </div>

        <h2 class="text-gray-900 font-[500] text-lg pb-2">Example Request Body:</h2>
        <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<pre class=" mt-2 text-[#b35e14]">{
  "action": "start"
}

</pre>
</div>'
    />





    <h2 class="text-gray-900 font-[500] text-lg pb-2">Error Response</h2>
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
    <h2 class="text-gray-900 font-[500] text-lg pb-2">Invalid or Non-existent Job ID</h2>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{
  "result": "Job not found. Invalid jobId",
  "success": false
}

</pre>
</div>'
/>
    <h2 class="text-gray-900 font-[500] text-lg pb-2">Job Not Ready for Verification</h2>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{
  "result": "Job is not ready for verification. Please check the /status endpoint.",
  "success": false
}


</pre>
</div>'
/>
    <h2 class="text-gray-900 font-[500] text-lg pb-2">Verification Already Started</h2>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{
  "result": "Verification has already started.",
  "success": false
}

</pre>
</div>'
/>
    <h2 class="text-gray-900 font-[500] text-lg pb-2">Account Restricted</h2>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{
  "result": "Verification restricted due to account status.",
  "success": false
}


</pre>
</div>'
/>

<h2 class="text-gray-900 font-[500] text-lg pb-2">Insufficient Verification Credits</h2>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 402 Payment Required</p>
<pre class=" mt-2 text-[#b35e14]">{
  "result": "Insufficient verification credits. Please top up your balance.",
  "success": false
}

</pre>
</div>'
/>



            {{-- next prev page --}} 
    <x-back-next-button-component 
    nextPageName="Check job status of a bulk email list" 
    nextUrl="{{ route('reference.page', ['page' => 'check-job-status-of-a-bulk-email-list']) }}" 
    prevUrl="{{ route('reference.page', ['page' => 'bulk-email-list']) }}" 
    prevPageName="Upload a bulk email list"
    />
    </div>
@endsection