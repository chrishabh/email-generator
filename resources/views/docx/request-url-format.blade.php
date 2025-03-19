@extends('layout.header2')

@push('title')
    <title>{{$title}}</title>
@endpush 

@section('content') 
<div class="prose max-w-none">
    <x-page-name-component pageName="Requests - URL Formats"/>
    <p class="text-gray-600 pb-6">
        The <strong>Bouncee </strong> API follows a structured URL format and supports authentication via API key.
    </p>
    
    {{-- example div --}}
    <div class="example mb-12">
        <x-api-view-component
            type="Request Format:"
            text="https://{hostname}/{api-version}/{path}?[parameters]"
        />
        <div class="pt-4">
            <h2 class=" text-gray-900 font-[500]">Components of the API are:</h2>
            <ul class="mt-2 list-disc pl-6 ">
                <li><strong>hostname:</strong> api.bouncee.net</li>
                <li><strong>api-version:</strong> The version of the API to be used (e.g., v1).</li>
                <li><strong>path:</strong> The specific API method being requested.</li>
                <li><strong>parameters:</strong> Query parameters required for the request.</li>
            </ul>
            <x-warning-text-component 
                text="All API requests must be made over HTTPS. HTTP is not supported."
            />
        </div>

        <x-api-view-component
            type="Example Request:"
            text="https://api.bouncee.net/v1/verify?apikey=232650svdvb174apwv0mbvu6syvf5tem&email=some@gmail.com"
        />
    </div>

    {{-- next prev page --}} 
    <x-back-next-button-component 
        nextPageName="Responses" 
        nextUrl="{{ route('reference.page', ['page' => 'responses']) }}" 
        prevUrl="{{ route('reference.page', ['page' => 'authentication']) }}" 
        prevPageName="Authentication"
    />

</div>
    @endsection