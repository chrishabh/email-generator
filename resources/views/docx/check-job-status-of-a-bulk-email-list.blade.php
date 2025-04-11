@extends('layout.header2')

@push('title')
    <title>{{$title}}</title>
@endpush

@section('content')

    <div class="prose max-w-none">
        <x-page-name-component 
        pageName='Check job status of a bulk email list <br> <div class="flex items-center space-x-2 mt-3">
            <span class="bg-green-600 text-white text-[9px] font-bold px-1 py-1 rounded-md">GET</span>
            <span class="text-[#384248] text-[11px]">https://api.bouncee.net/v1/bulk</span>
        </div>'/>

        <p class="text-gray-600 pb-6">
            This endpoint allows you to get the current status of the bulk email list. It returns five different statuses such as preparing, ready, verifying, completed, failed and cancelled. If the status of the list is 'ready', you can start verification of the list and if the status of the list is 'completed', you can download the result of the list. It also returns the analysis and verification results of the bulk email list.
        </p>


        <h2 class="text-gray-900 font-[500] text-lg pb-2">Successful Response</h2>
        <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 200 OK</p>
<pre class=" mt-2 text-[#b35e14]">{
    "job_id": "r374aki32rnatv868nntpxloc7dkilszc3eu",
    "status": "completed",
    "created_at": "08/13/2021, 9:39:37 AM",
    "total": 2,
    "verified": 2,
    "pending": 0,
    "analysis": {
        "common_isp": 1,
        "role_based": 1,
        "disposable": 0,
        "spamtrap": 0,
        "syntax_error": 0
    },
    "results": {
        "deliverable": 1,
        "undeliverable": 0,
        "accept_all": 1,
        "unknown": 0
    },
    "success": true,
    "message": "Verification completed successfully. Please download the result using /download endpoint"
}


</pre>
</div>'
    />


 
        <h2 class=" text-gray-900 font-[500] text-lg pb-2 pt-12">Response Parameters</h2>
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
                        <td class="p-3 border border-gray-300">job_id</td>
                        <td class="p-3 border border-gray-300">[string]</td> 
                        <td class="p-3 border border-gray-300">The job_id corresponding to the list you need to check the status</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">status</td>
                        <td class="p-3 border border-gray-300">[string]</td> 
                        <td class="p-3 border border-gray-300">The status of the list</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">created</td>
                        <td class="p-3 border border-gray-300">[date time]</td> 
                        <td class="p-3 border border-gray-300">The date and time in which the list has been created</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">total</td>
                        <td class="p-3 border border-gray-300">[Integer]</td> 
                        <td class="p-3 border border-gray-300">The total number of emails the list has</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">verified</td>
                        <td class="p-3 border border-gray-300">[Integer]</td> 
                        <td class="p-3 border border-gray-300">The number of emails are verified</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">pending</td>
                        <td class="p-3 border border-gray-300">[Integer]</td> 
                        <td class="p-3 border border-gray-300">The number of emails need to be verified</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">common_isp</td>
                        <td class="p-3 border border-gray-300">[0,1]</td> 
                        <td class="p-3 border border-gray-300">Whether the email is considered a role address. (e.g. "sales@, info@, help@, etc.)</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">role-based</td>
                        <td class="p-3 border border-gray-300">[0,1]</td> 
                        <td class="p-3 border border-gray-300">Whether the email is hosted by a free email provider like Gmail, Yahoo!, Hotmail etc..</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">disposable</td>
                        <td class="p-3 border border-gray-300">[0,1]</td> 
                        <td class="p-3 border border-gray-300">Whether this is a temporary email.</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">spamtrap</td>
                        <td class="p-3 border border-gray-300">[0,1]</td> 
                        <td class="p-3 border border-gray-300">Is this a honey-trap email
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">syntax_error</td>
                        <td class="p-3 border border-gray-300">[0,1]</td> 
                        <td class="p-3 border border-gray-300">Whether the email is syntactically incorrect
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">deliverable</td>
                        <td class="p-3 border border-gray-300">[Integer]</td> 
                        <td class="p-3 border border-gray-300">These emails are valid and safe to send mail.
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">undeliverable</td>
                        <td class="p-3 border border-gray-300">[Integer]</td> 
                        <td class="p-3 border border-gray-300">Sending mails will result in bounce.
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">accept_all</td>
                        <td class="p-3 border border-gray-300">[Integer]</td> 
                        <td class="p-3 border border-gray-300">Remote host accepts mail at any address.
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">unknown</td>
                        <td class="p-3 border border-gray-300">[Integer]</td> 
                        <td class="p-3 border border-gray-300">Unable to definitively verify these emails as their mail servers were not reachable during verification.
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">success</td>
                        <td class="p-3 border border-gray-300">[true, false]</td> 
                        <td class="p-3 border border-gray-300">Whether the API request call was successful or not.
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">message</td>
                        <td class="p-3 border border-gray-300">[string]</td> 
                        <td class="p-3 border border-gray-300">Describes API result
                    </tr>
                </tbody>
            </table>
        </div>

 
 

 
    <h2 class="text-gray-900 font-[500] text-lg pb-2 pt-8 ">Other Responses</h2> 
    <p class="text-gray-600 pt-8">
        Invalid or Missing Email Address:
    </p>

    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 401 Unauthorized</p>
<pre class=" mt-2 text-[#b35e14]">{   
   "result":"Invalid API Key",
   "success": false
}

</pre>
</div>'
/> 
    <p class="text-gray-600 pt-8">
        The response you get when you provide invalid job_id:
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
    The response you get still the job is preparing:
</p>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 200 OK</p>
<pre class=" mt-2 text-[#b35e14]">{
    "job_id": "r374aki32rnatv868nntpxloc7dkilszc3eu",
    "status": "preparing",
    "created_at": "08/13/2021, 9:39:37 AM",
    "success": true,
    "message": "Job is being prepared for verification"
}


</pre>
</div>'
/>

<p class="text-gray-600 pt-8">
    The response you get when the list is ready for verification:
</p>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 200 OK</p>
<pre class=" mt-2 text-[#b35e14]">{
    "job_id": "r374aki32rnatv868nntpxloc7dkilszc3eu",
    "status": "ready",
    "created_at": "08/13/2021, 9:39:37 AM",
    "total": 2,
    "analysis": {
        "common_isp": 1,
        "role_based": 1,
        "disposable": 0,
        "spamtrap": 0,
        "syntax_error": 0
    },
    "success": true,
    "message": "Job ready for verification. Please begin verification to know the result"
}


</pre>
</div>'
/>
 
<p class="text-gray-600 pt-8">
    The response you get when the job failed:
</p>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 200 OK</p>
<pre class=" mt-2 text-[#b35e14]">{
    "job_id": "565ad4aki32rnqweqaefwdaosjc7dksdaksd",
    "status": "failed",
    "created_at": "08/03/2021, 3:35:32 PM",
    "total": 0,
    "success": true,
    "message": "Unable to process your job, The uploaded list contains invalid data"
}


</pre>
</div>'
/> 
<p class="text-gray-600 pt-8">
    The response you get when the job is cancelled:
</p>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 200 OK</p>
<pre class=" mt-2 text-[#b35e14]">{
    "job_id": "asdfcqkahs37e2137wh273yhen283ye0js23",
    "status": "cancelled",
    "created_at": "08/05/2021, 12:07:21 PM",
    "total": 4,
    "success": true,
    "message": "Job is cancelled"
}


</pre>
</div>'
/> 
<p class="text-gray-600  pt-8">
    The response you get when the job not found:
</p>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 BadRequest</p>
<pre class=" mt-2 text-[#b35e14]">{
    "result": "Job not found",
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