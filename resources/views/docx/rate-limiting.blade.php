@extends('layout.header2')
@push('title')
    <title>{{$title}}</title>    
@endpush

@section('content')
    <div class="prose max-w-none">
        <x-page-name-component pageName="Rate Limiting"/>
        <p class="text-gray-600 pb-6">
            The <strong>Bouncee</strong> API enforces rate limits to ensure fair usage and system stability. You can make up to <strong>120 requests per minute</strong> per API endpoint.
        </p>
        
        <h2 class=" text-gray-900 font-[500] text-lg pb-2">Exceeding the Rate Limit</h2>
        <p class="text-gray-600 pb-6">
            If the rate limit is exceeded, the API will return the following error response:
        </p>

        <x-api-view-component
        text='<div class="bg-gray-800 text-green-400 font-mono text-sm p-4 rounded-lg mt-4">
<p class="text-green-500">HTTP/1.1 429 Too Many Requests</p>
<pre class=" mt-2 text-[#b35e14]">{
"success":"false",
"code":429,
"result":"Too Many Requests"
}
</pre>
</div>'
    />

         {{-- next prev page --}} 
        <x-back-next-button-component 
            nextPageName="HTTP Status Codes" 
            nextUrl="{{ route('reference.page', ['page' => 'http-status-codes']) }}" 
            prevUrl="{{ route('reference.page', ['page' => 'responses']) }}" 
            prevPageName="Responses"
        />
    </div>
@endsection