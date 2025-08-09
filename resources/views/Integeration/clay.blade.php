@extends('layout.main')
@php
    // $headerData = array();
    $headerData['whichPageRequest'] = 'clay';
@endphp
@section('main-section')
    @push('styles')
        <script src="{{ asset('integration/css/tailwind/script.js') }}"></script>
    @endpush

    <div id="custom-api-section" class="container1 mx-auto p-4">
         <div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">API INTEGRATIONS OF CLAY</h2>

        <div class="bg-white rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Email Validation Integration for Clay.com</h3>

            <p class="text-gray-600 mb-4">
                Clay.com is a lead-generation outbound platform that helps businesses build and enrich lead lists, write high-converting, personalized emails, and leverage the power of AI for content creation.
                Clay.com makes it simple to connect your company’s CRM, social media profiles, scraping tools, and email services. Their impressive list of prospecting integrations now includes <b>Bouncee’s email validation service</b>, so you can automatically validate new leads.
            </p>
            <div class="mt-3"></div>
            <h2 class="text-[1rem] font-bold text-gray-700 mb-2">Prerequisites</h2>
            <div class="text-gray-600 mb-6"> 
                <div class> To use Bouncee email validation, you’ll require a Clay.com account at a minimum. Clay.com provides an API key for all users, and you can validate an email address at the cost of <b> 1 Clay Credit.</b></div>
                <div class> If you have an existing Bouncee account, you can save your Clay Credits and use your Bouncee credits instead. The instructions below will walk you through how to add and authenticate your <b> Bouncee API key</b> within your Clay.com account settings.</div>

            </div> 
            <!-- Step 1 -->
            <div class="mb-6">
                <h4 class="text-[1rem] font-bold text-gray-700 mb-2">Step 1</h4>
                <p class="text-gray-600">
                    Sign in to your Clay.com account. You should automatically be redirected to <b>My Workspace</b>.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="mb-6">
                <h4 class="text-[1rem] font-bold text-gray-700 mb-2">Step 2</h4>
                <p class="text-gray-600 mb-4"> 
                    Navigate to any existing table that contains contacts with email addresses.
                    <div> Select <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded">+ Enrich Data</span>   from the top-right corner. </div>
                </p>
                <img src="{{ asset('integration/image/clay/1.png') }}" alt="Enrich Data Button Screenshot" class="w-[80%] shadow-lg my-10">
                <img src="{{ asset('integration/image/clay/2.png') }}" alt="Enrich Data Button Screenshot" class="w-[80%] shadow-lg my-10">
            </div>

            <!-- Step 3 -->
            <div class="mb-6">
                <h4 class="text-[1rem] font-bold text-gray-700 mb-2">Step 3</h4>
                <p class="text-gray-600 mb-4">
                    In the left menu, go to <b>People → Emails,</b> then search for <b> Validate Email - Bouncee</b> on the right.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="mb-6">
                <h4 class="text-[1rem] font-bold text-gray-700 mb-2">Step 4</h4>
                <p class="text-gray-600"> 
                    <ul class="mt-2 list-disc pl-10">
                        <li class="list-disc text-gray-600">If you choose to use Clay.com’s Bouncee API key, each email address you validate will consume <b> 1 Clay Credit </b> from your account.</li>
                        <li class="list-disc text-gray-600">If you choose to use your own Bouncee API key, your <b>Bouncee credits</b>  will be used instead.</li> 
                    </ul>
                </p>
            </div>
            <!-- Step 5 -->
            <div class="mb-6">
                <h4 class="text-[1rem] font-bold text-gray-700 mb-2">Step 5</h4>
                <p class="text-gray-600 mb-4">
                    If you choose to use your own Bouncee API key, click the dropdown and select <b>Add an account.</b>
                </p>
            </div>
            <!-- Step  6-->
            <div class="mb-6">
                <h4 class="text-[1rem] font-bold text-gray-700 mb-2">Step 6</h4>
                <p class="text-gray-600 mb-4">
                    Open a new browser tab and log in to your Bouncee account. Go to your <b>API Settings</b> to retrieve your API key or generate a new one.
                    <div>Copy your API key.</div>
                </p>
            </div>
            <!-- Step  7-->
            <div class="mb-6">
                <h4 class="text-[1rem] font-bold text-gray-700 mb-2">Step 7</h4>
                <p class="text-gray-600">  
                    <ul class="mt-2 list-disc pl-10">
                        <li class="list-disc">Return to Clay.com and paste your Bouncee API key in the field provided.</li>
                        <li class="list-disc">Click Authenticate.</li> 
                        <li class="list-disc">Once authentication is complete, your Bouncee account email will appear in the dropdown list. You can now use your Bouncee account to validate emails for new incoming leads.</li> 
                    </ul>
                </p>
            </div>
            <!-- Step  8-->
            <div class="mb-6">
                <h4 class="text-[1rem] font-bold text-gray-700 mb-2">Step 8</h4>
                <p class="text-gray-600"> 
                    <ul class="mt-2 list-disc pl-10"> 
                        <li class="list-disc">In the same setup menu, go to <b>SETUP INPUTS.</b></li>
                        <li class="list-disc">Select the column that contains the email addresses you want to validate (e.g., "Email").</li> 
                        <li class="list-disc">Click<b> Continue to Add Fields.</b></li> 
                    </ul>
                </p>
            </div>
            <!-- Step  9-->
            <div class="mb-6">
                <h4 class="text-[1rem] font-bold text-gray-700 mb-2">Step 9</h4>
                <p class="text-gray-600">   
                    <ul class="mt-2 list-disc pl-10"> 
                        <li class="list-disc">Choose whether to create new columns to store additional data returned during the email validation process (e.g., status, SMTP details, sub-status, free email indicator).</li> 
                        <li class="list-disc">Click<b> Save & Run.</b></li> 
                    </ul>
                </p>
            </div>

            <p class="text-gray-600 mb-4">
                The email validation process will run automatically, and a new column (e.g., <b>Validate Email </b>) will appear in your table with the validation results for each address.
                <br>To retun the validation, simply click the <b> play</b> button in the column header.
                <br><br>
                If you have any issues or questions about the Clay.com integration, please contact the <b>Bouncee Support Team </b>— available 24/7.
            </p> 
        </div>
    </div>
</div>

    </div>
@endsection


@section('specificScript')
@endsection
