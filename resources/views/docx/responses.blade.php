@extends('layout.header2')

@push('title')
    <title>{{$title}}</title>
@endpush

@section('content')

    <div class="prose max-w-none">
        <x-page-name-component pageName="Responses"/>
        <p class="text-gray-600 pb-6">
            All requests to the <strong>Bouncee</strong> API are made over <strong>HTTPS</strong>, and responses are returned in <strong>JSON</strong> format. Every response includes a success parameter to indicate whether the request was successfully processed or if an error occurred.
        </p>

        <h2 class=" text-gray-900 font-[500] text-lg pb-2">Handling Responses</h2>
        <p class="text-gray-600 pb-6">
            Always check the <strong>HTTP status code </strong> to  verify whether the request was received and processed successfully.
        </p>

        <h2 class=" text-gray-900 font-[500] text-lg pb-2">Success Response</h2>
        <p class="text-gray-600 pb-6">
            When a request is successful, the response follows this format:
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

        <p class="text-gray-600 pt-5">The result parameter provides the status of the email address.</p>

        <h2 class=" text-gray-900 font-[500] text-lg pb-2">Error Response</h2>
        <p class="text-gray-600 pb-6">
            If an error occurs, the API will return a response in the following format:
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



     {{-- next prev page --}} 
     <x-back-next-button-component 
        nextPageName="Rate Limiting" 
        nextUrl="{{ route('reference.page', ['page' => 'rate-limiting']) }}" 
        prevUrl="{{ route('reference.page', ['page' => 'request-url-format']) }}" 
        prevPageName="Request- URL Formats"
    />
    </div>
@endsection