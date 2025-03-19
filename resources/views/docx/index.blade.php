@extends('layout.header2')
    @push('title')
    <title>Bouncify API Docs</title> 
    @endpush 

    @section('content') 
        <div class="prose max-w-none">
            <h1 class="text-3xl font-bold mb-4 leading-1.2 text-1.9">Bouncee API Documentation</h1>
            <p class="text-lg text-gray-600 ">Overview of Email Validation API</p>
            <hr class="my-6 border-gray-300">
            
            <section id="installation" class="mb-12">
                <h2 class="text-2xl font-semibold mb-4">Welcome to the <b>Bouncee</b> Email Validation API documentation!</h2>
                <p class="text-gray-600 mb-4">
                    The <b>Bouncee</b> Email Verification API enables real-time email validation to help you maintain a clean and accurate email list. Easily integrate our API into your website, application, or system to verify email addresses at the point of collection and improve deliverability.
                </p>
                <h2 class="text-2xl font-semibold mb-4">How It Works</h2>
                <p class="mt-2 text-gray-700">
                    The <b>Bouncee</b> API follows a RESTful architecture, ensuring seamless integration with your platform. All requests are made over <b>HTTPS</b>, and responses are returned in <b>JSON</b> format.
                </p>

                {{-- <pre class="bg-gray-800 text-white p-4 rounded-lg">composer create-project laravel/laravel example-app</pre> --}}
            </section>

            <section id="help-section" class="mb-12">
                <div class="mt-6 p-4 bg-[#e3edf2] border-l-4 border-[#5BC0DE]">
                    <p class="font-semibold text-[#46b8da]"> 
                        <span class="text-2xl mr-2">🔵</span> Need Assistance?
                    </p> 
                    <p class="text-grey-700 mt-1">
                        If you have any questions about integrating or using the <b>Bouncee</b> Email Validation API, feel free to reach out to our 
                        <a class="text-[#46b8da] font-semibold underline" href="#">support team </a> via a ticket or live chat.
                    </p>
                </div> 
            </section>
            
            {{-- next-page-link --}}
            <div class="mt-8 flex justify-end">
                <a href="{{ route('reference.page', ['page' =>'authentication']) }}" class="text-gray-600   hover:undeline hover:text-[orange]">Authentication →</a>
            </div>
        </div>
    @endsection