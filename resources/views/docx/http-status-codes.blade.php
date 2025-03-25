@extends('layout.header2')
@push('title')
    <title>{{$title}}</title>
@endpush


@section('content')
    <div class="prose max-w-none">
        <x-page-name-component pageName="HTTP Status Codes"/>
        <p class="text-gray-600 pb-6">
            The <strong>Bouncee</strong> API uses standard HTTP status codes to indicate the outcome of each request. Below is a list of possible responses:
        </p>
    </div>


    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left border border-gray-300 font-semibold">Status Code</th>
                    <th class="p-3 text-left border border-gray-300 font-semibold">Name</th>
                    <th class="p-3 text-left border border-gray-300 font-semibold">Description</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border border-gray-300">
                    <td class="p-3 border border-gray-300">200</td>
                    <td class="p-3 border border-gray-300">OK</td>
                    <td class="p-3 border border-gray-300">The request was successful</td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="p-3 border border-gray-300">401</td>
                    <td class="p-3 border border-gray-300">Unauthorized</td>
                    <td class="p-3 border border-gray-300">Invalid API key</td>
                </tr>
                <tr class="border border-gray-300">
                    <td class="p-3 border border-gray-300">402</td>
                    <td class="p-3 border border-gray-300">Payment Required</td>
                    <td class="p-3 border border-gray-300">
                        You are running out of verification credits. 
                        <a href="#" class="text-blue-600 underline">buy credits</a>.
                    </td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="p-3 border border-gray-300">403</td>
                    <td class="p-3 border border-gray-300">Forbidden</td>
                    <td class="p-3 border border-gray-300">Request not allowed</td>
                </tr>
                <tr class="border border-gray-300">
                    <td class="p-3 border border-gray-300">429</td>
                    <td class="p-3 border border-gray-300">Too Many Requests</td>
                    <td class="p-3 border border-gray-300">
                        Too many requests. 
                        <a href="#" class="text-blue-600 underline">Rate limit</a> exceeded.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>


        {{-- next prev page --}} 
        <x-back-next-button-component 
            nextPageName="Single Validation API" 
            nextUrl="{{ route('reference.page', ['page' => 'single-validation-api']) }}" 
            prevUrl="{{ route('reference.page', ['page' => 'rate-limiting']) }}" 
            prevPageName="Rate Limiting"
        />

@endsection