@extends('layout.header2')

@push('title')
    <title>{{$title}}</title>
@endpush

@section('content')

    <div class="prose max-w-none">
        <x-page-name-component 
        pageName='Delete Bulk Email List <br> <div class="flex items-center space-x-2 mt-3">
            <span class="bg-red-600 text-white text-[9px] font-bold px-1 py-1 rounded-md">DELETE</span>
            <span class="text-[#384248] text-[11px]">http://localhost:8000/api/v1/bulk/{job_id}?apiKey=094e913b-e0bc-4e3b-b876-575f011774c6</span>
        </div>'/>

        <p class="text-gray-600 pb-6">
            Use this endpoint to permanently remove an uploaded email list from the Bouncee platform. Deletion is only allowed if the list is in the <strong>ready</strong> or <strong>completed</strong> status. You cannot delete a list that is currently under verification or still processing.
        </p>


        
    <x-warning-text-component 
    text="<strong>Deleting a list is irreversible.</strong><br>
Once removed, both the uploaded data and its results are permanently erased. To revalidate, the list must be uploaded and processed again.
"
/>

        <h2 class="text-gray-900 font-[500] text-lg pb-2">Successful Response</h2>
        <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 200 OK</p>
<pre class=" mt-2 text-[#b35e14]">{
  "job_id": "r374aki32rnatv868nntpxloc7dkilszc3eu",
  "success": true,
  "message": "List will be deleted"
}

</pre>
</div>'
    />


 
        <h2 class=" text-gray-900 font-[500] text-lg pb-2 pt-12">Response Fields</h2>
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
                        <td class="p-3 border border-gray-300">job_id</td>
                        <td class="p-3 border border-gray-300">[string]</td> 
                        <td class="p-3 border border-gray-300">The unique ID of the job being deleted</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">success</td>
                        <td class="p-3 border border-gray-300">[boolean]</td> 
                        <td class="p-3 border border-gray-300">Indicates whether the deletion request succeeded</td>
                    </tr>
                    <tr class="border border-gray-300">
                        <td class="p-3 border border-gray-300">message</td>
                        <td class="p-3 border border-gray-300">[string]</td> 
                        <td class="p-3 border border-gray-300">A description of the outcome</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        <h2 class=" text-gray-900 font-[500] text-lg pb-2 pt-12">Required Parameters</h2>
        <p class="text-gray-900 font-[500] pb-2"> Path Parameter</p>
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
                        <td class="p-3 border border-gray-300">ID of the bulk job you want to delete</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="text-gray-900 font-[500] pb-2"> Query Parameter</p>
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
                        <td class="p-3 border border-gray-300">apiKey</td>
                        <td class="p-3 border border-gray-300">[string]</td> 
                        <td class="p-3 border border-gray-300">Yes</td> 
                        <td class="p-3 border border-gray-300">Your Bouncee API key</td>
                    </tr>
                </tbody>
            </table>
        </div>

 
 

 
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
    "result": "Job not found. 
    Invalid jobId", 
    "success": false
}
</pre>
</div>'
/> 
    <p class="text-gray-600 pt-8">
        List is still processing or verifying:
    </p>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{ 
    "result": "List is being processed, and cannot be deleted.", 
    "success": false 
}
</pre>
</div>'
/>
 
<p class="text-gray-600 pt-8">
    List already deleted or not found
</p>
    <x-api-view-component
    text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 400 Bad Request</p>
<pre class=" mt-2 text-[#b35e14]">{
    "result": "List not found, may be already deleted.", 
    "success": false 
}


</pre>
</div>'
/>
 

            {{-- next prev page --}} 
    <x-back-next-button-component   
    prevUrl="{{ route('reference.page', ['page' => 'bulk-email-list']) }}" 
    prevPageName="Upload a bulk email list"
    nextPageName="" 
    nextUrl="" 
    />
    </div>
@endsection