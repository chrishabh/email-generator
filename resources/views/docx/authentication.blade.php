@extends('layout.header2')
@push('title')
    <title>{{$title}}</title>
@endpush

@section('content')
    <div class="prose max-w-none">
        <h1 class="text-2xl  font-bold mb-[1.4em] pb-[2em] border-b-1 border-[#0000001A] leading-1.2 text-1.9">Authentication</h1>
        <p class="text-gray-600 mb-4">
            <b>Bouncee</b> uses API keys to authenticate access to the Email Validation API. To obtain your API key, log in to your <b>Bouncee</b> account and navigate to the API section, where you'll find your unique API key.
        </p> 

        <section id="help-section" class="mb-12">
            <div class="mt-6 p-4 bg-[#e3edf2] border-l-4 border-[#5BC0DE] rounded-lg">
                <p class="font-semibold text-[#46b8da]"> 
                    <span class="text-2xl mr-2"></span> API Key 
                </p> 
                <p class="text-grey-700 mt-1">
                    Authenticate your requests by including this API key as the <span class="bg-[#f6f8fa] rounded px-1 py-1 mx-1">apikey</span> parameter in the request's query string.
                </p>
            </div> 
        </section>

        {{-- example div --}}
        <div class="example mb-12">
            <h2 class="font-semibold text-lg pb-4">Example:</h2>
            <div class="bg-gray-800 text-white p-4 rounded-lg">
                <p class="text-[orange] pb-3 text-lg">cURL</p>
               curl &nbsp;-X  &nbsp; GET &nbsp; <span class="text-[#b35e14] font-semibold"> 'https://bouncee.net/api/v1/verify?apikey=your_api_key&email=some@gmail.com'</span>
            </div>
            <p class="bg-[#fcf8f2] border-l-4 border-[#eea236] p-6 my-9 rounded-lg"><span class="text-2xl pr-2"> 🚧</span>Replace your_api_key with your actual API key before making requests.</p>
        </div>

        {{-- next prev page --}}
        <div class="mt-8 flex justify-between items-center b-t border-gray-400 py-4">
            <a href="{{ route('reference.page', ['page' =>'overview']) }}" class=" text-gray-600 hover:undeline hover:text-[orange]">
                 ← Bouncify API Documentation
            </a>
            <a href="{{ route('reference.page', ['page' =>'request-url-format']) }}" class=" text-gray-600  hover:undeline hover:text-[orange]">
                Requests - URL Formats →
                 
            </a>
        </div>

    </div>
@endsection
