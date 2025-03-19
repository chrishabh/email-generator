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

        <h2 class=" text-gray-900 font-[500]">Handling Responses</h2>
        <p class="text-gray-600 pb-6">
            Always check the <strong>HTTP status code </strong> to  verify whether the request was received and processed successfully.
        </p>

        <h2 class=" text-gray-900 font-[500]">Success Response</h2>
        <p class="text-gray-600 pb-6">
            When a request is successful, the response follows this format:
        </p>

    </div>
@endsection