@extends('layout.header2')

@push('title')
    <title>{{$title}}</title>
@endpush

@section('content')

    <div class="prose max-w-none">
        <x-page-name-component 
        pageName='Download Verified Email List <br> <div class="flex items-center space-x-2 mt-3">
            <span class="bg-green-600 text-white text-[9px] font-bold px-1 py-1 rounded-md">POST</span>
            <span class="text-[#384248] text-[11px]">https://api.bouncee.net/v1/download</span>
        </div>'/>

        <p class="text-gray-600 pb-6">
            This endpoint lets you download the results of a completed bulk email verification job. Once the job status is marked as <strong>completed</strong>, the data can be downloaded in .csv format. You can filter by result types to get only the emails you're interested in.
        </p>

        <h1 class="text-gray-900 font-[500] text-lg pb-2">Supported Result Types</h1>
        <p class="text-gray-600 pb-6">
            •	<strong> deliverable </strong>– Verified and safe to send
            •	<strong> undeliverable  </strong> – Invalid or unreachable
            •	<strong>accept_all </strong>– Domains that accept any address without validation
            •	<strong> unknown </strong>– Unable to verify due to server issues
            
        </p>


        <h2 class="text-gray-900 font-[500] text-lg pb-2">Successful CSV Response Format</h2>
        <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 200 OK</p>
<pre class=" mt-2 text-[#b35e14]">"Email", "Verification Result", "Syntax Error", "ISP", "Role", "Disposable", "Trap", "Verified At"
"info@example.com", "deliverable", "N", "Y", "N", "N", "N", "2025-04-08T06:49:39.280Z"
"support@demo.org", "accept-all", "N", "N", "Y", "Y", "N", "2025-04-08T06:49:39.282Z"



</pre>
</div>'
    />

 

 
        <h2 class=" text-gray-900 font-[500] text-lg pb-2 pt-12">Response Field Details</h2>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr> 
                        <th class="p-3 text-left border border-gray-300 font-semibold">Field</th>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Type</th> 
                        <th class="p-3 text-left border border-gray-300 font-semibold">Definition</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">Email</td>
                        <td class="p-3 border border-gray-300">[string]</td> 
                        <td class="p-3 border border-gray-300">The email address that was verified</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">Verification Result</td>
                        <td class="p-3 border border-gray-300">[string]</td> 
                        <td class="p-3 border border-gray-300">One of: deliverable, undeliverable, accept-all, or unknown</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">Syntax Error</td>
                        <td class="p-3 border border-gray-300">["Y"/"N"]</td> 
                        <td class="p-3 border border-gray-300">Indicates whether the email format is invalid</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">ISP</td>
                        <td class="p-3 border border-gray-300">["Y"/"N"]</td> 
                        <td class="p-3 border border-gray-300">Identifies if the domain belongs to a public email provider (e.g. Gmail)</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">Role</td>
                        <td class="p-3 border border-gray-300">["Y"/"N"]</td> 
                        <td class="p-3 border border-gray-300">Indicates whether the email is a role-based address (e.g., info@, sales@)</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">Disposable</td>
                        <td class="p-3 border border-gray-300">["Y"/"N"]</td> 
                        <td class="p-3 border border-gray-300">Temporary or one-time email addresses</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">Trap</td>
                        <td class="p-3 border border-gray-300">["Y"/"N"]</td> 
                        <td class="p-3 border border-gray-300">Flags if the address is a spamtrap</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">Verified At</td>
                        <td class="p-3 border border-gray-300">[datetime]</td> 
                        <td class="p-3 border border-gray-300">The timestamp when the email was last verified</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h2 class=" text-gray-900 font-[500] text-lg pb-2 pt-12">Required Parameters</h2>
        <p class="text-gray-600 pb-6"> Query Parameters</p>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left border border-gray-300 font-semibold">Name</th>
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
                        <td class="p-3 border border-gray-300">Unique ID of the verification job</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">api_key</td>
                        <td class="p-3 border border-gray-300">[string]</td>
                        <td class="p-3 border border-gray-300">Yes</td>
                        <td class="p-3 border border-gray-300">Your Bouncee API key</td>
                    </tr>
                     
                </tbody>
            </table>
        </div>
 
        <h2 class="text-gray-900 font-[500] text-lg pb-2 pt-8">JSON Body:</h2>
        <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<pre class=" mt-2 text-[#b35e14]">{
  "filterResult": ["deliverable", "undeliverable", "accept_all", "unknown"]
}

</pre>
</div>'
    />



    

    <p class="text-gray-600 pb-6"> You can include one or more result types in the array to filter what’s downloaded.</p>
   
    <h2 class="text-gray-900 font-[500] text-lg pb-2 pt-8 ">Error Responses</h2> 
    <p class="text-gray-600 pt-8">
        Invalid API Key:
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
    <p class="text-gray-600 pt-8">
        Invalid Job ID:
    </p>

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
    <p class="text-gray-600 pt-8">
        Account Restricted:
    </p>

    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]"> { 
    "result": "DOWNLOAD-RESTRICTED",
    "success": false 
}
</pre>
</div>'
/> 
    <p class="text-gray-600 pt-8">
        Incorrect filterResult Options:
    </p>

    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{ 
    "result": "Invalid filterResult. Please provide correct filterResult options", 
    "success": false
}
</pre>
</div>'
/> 
    <p class="text-gray-600 pt-8">
        Job is Still Verifying:
    </p>

    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{ 
    "result": "Job is being verified, please wait until it completes.", 
    "success": false
}
</pre>
</div>'
/> 
    <p class="text-gray-600 pt-8">
        Job Not Started Yet:
    </p>

    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 401 Unauthorized</p>
<pre class=" mt-2 text-[#b35e14]">{ 
     "result": "Job is ready for verification, please start verification and download
        your results once list verified.", 
     "success": false
}
</pre>
</div>'
/> 
    <p class="text-gray-600 pt-8">
        Job Still Preparing:
    </p>

    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{
    "result": "Job is being prepared for verification, please start verifying and 
        then download your result.",
    "success": false 
}
</pre>
</div>'
/> 
    <p class="text-gray-600 pt-8">
        Uploaded List Contains Invalid Data:
    </p>

    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{ 
    "result": "List cannot be downloaded, The uploaded list contains invalid data.",
    "success": false 
}
</pre>
</div>'
/> 



            {{-- next prev page --}} 
    <x-back-next-button-component 
    nextPageName="Delete a bulk email list" 
    nextUrl="{{ route('reference.page', ['page' => ' delete-a-bulk-email-list']) }}" 
    prevUrl="{{ route('reference.page', ['page' => 'check-job-status-of-a-bulk-email-list']) }}" 
    prevPageName="Check job status of a bulk email list"
    />
    </div>
@endsection