@extends('layout.main')

@php
$headerData = array();
$headerData['whichPageRequest'] ='singlePage';
@endphp

@section('main-section')
@push('title')
<title>Email Verification Integrations – Connect Bouncee with Your Tools</title>
<meta name="description" content="Connect Bouncee’s email verification to Mailchimp, HubSpot, Google Sheets, and more. Clean email data across platforms effortlessly with our easy-to-use integrations.">
<meta name="keywords" content="email verification integrations, bouncee integrations, mailchimp, hubspot, google sheets, clean email data, email tools">
<meta name="author" content="bouncee">

<!-- Open Graph / Facebook -->
<meta property="og:title" content="Email Verification Integrations – Connect Bouncee with Your Tools">
<meta property="og:description" content="Connect Bouncee’s email verification to Mailchimp, HubSpot, Google Sheets, and more. Clean email data across platforms effortlessly with our easy-to-use integrations.">
<meta property="og:image" content="https://bouncee.net/assets/img/logo.png">
<meta property="og:type" content="website">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Email Verification Integrations – Connect Bouncee with Your Tools">
<meta name="twitter:description" content="Connect Bouncee’s email verification to Mailchimp, HubSpot, Google Sheets, and more. Clean email data across platforms effortlessly with our easy-to-use integrations.">
<meta name="twitter:image" content="https://bouncee.net/assets/img/logo.png">
@endpush
@push('styles')
<style>
    .integration-logo {
        width: 60px;
        /* Adjust as needed */
        height: 60px;
        /* Adjust as needed */
        object-fit: contain;
    }

    .text-brand-dark-blue {
        color: #1e293b;
    }

    .text-brand-gray {
        color: #6b7280;
    }

    .intg_img {
        width: 50px;
        max-height: 3em;
        max-width: 3em;
        margin-bottom: 0.5em;
    }

    .integration_item {
        padding: 1.5em;
        border: 1px solid #e5e7eb;
        border-radius: 0.5em;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .integration_item:hover {
        transform: translateY(-0.5em);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .integration_item h4 {
        font-size: 1rem;
        color: #374151;
        /* Tailwind's gray-700 */
    }

    .integration_item img {
        width: 50px;
        height: 50px;
        object-fit: contain;
        margin-bottom: 0.5em;
    }

    .integration_item a {
        text-decoration: none;
        color: inherit;
    }

    .integration_item a:hover {
        text-decoration: none;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .col-lg-3,
    .col-sm-6 {
        flex: 1 0 21%;
        /* Adjusts to 4 items per row */
        margin: 0.5em;
    }

    @media (max-width: 768px) {

        .col-lg-3,
        .col-sm-6 {
            flex: 1 0 46%;
            /* Adjusts to 2 items per row on smaller screens */
        }
    }

    @media (max-width: 576px) {

        .col-lg-3,
        .col-sm-6 {
            flex: 1 0 100%;
            /* Stacks items on top of each other on very small screens */
        }
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .text-center {
        text-align: center;
    }

    .mb-12 {
        margin-bottom: 3rem;
    }

    .mb-4 {
        margin-bottom: 1rem;
    }

    .f_400 {
        font-weight: 400;
    }

    .f_size_16 {
        font-size: 1rem;
    }

    .l_height28 {
        line-height: 1.75;
    }
</style>
@endpush

<body class="bg-white">

    <div class="container mx-auto px-4 py-12 md:py-16">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-brand-dark-blue mb-4" style="margin-top: 4em;">Works Seamlessly with Your Tools</h1>
            <p class="text-lg md:text-xl text-brand-gray">Connect Bouncee to Mailchimp, HubSpot, Google Sheets, WordPress, and more. Clean your data across platforms with minimal effort.</p>
        </div>

        <div class="row">
            @foreach($tools as $tool)
            <div class="col-lg-3 col-sm-6 my-4">
                <a href="">
                    <div class="integration_item text-center">
                        <img class="intg_img" src="{{ asset($tool['icon_url']) }}" alt="{{ $tool['name'] }}">
                        <h4 class="f_400 f_size_16 l_height28 t_color2 mb-0">{{ $tool['name'] }}</h4>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    </div>

</body>
@endsection