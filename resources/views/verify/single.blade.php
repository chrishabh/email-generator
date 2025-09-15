@extends('layout.main')

@section('main-section')
@extends('layout.main')
    @push('title')
        <title>Spam & Invalid Email Checker – Verify Email for Free</title>
        <meta name="description" content="Instantly check if an email is disposable, invalid, or spam-trap. Use Bouncee’s free email address verifier to ensure deliverability and protect your sending reputation.">
        <meta name="keywords" content="spam email checker, invalid email verification, disposable email, email deliverability, email verification free, bouncee">
        <meta name="author" content="bouncee">

        <!-- Open Graph / Facebook -->
        <meta property="og:title" content="Spam & Invalid Email Checker – Verify Email for Free">
        <meta property="og:description" content="Instantly check if an email is disposable, invalid, or spam-trap. Use Bouncee’s free email address verifier to ensure deliverability and protect your sending reputation.">
        <meta property="og:image" content="https://bouncee.net/assets/img/logo.png">
        <meta property="og:type" content="website">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Spam & Invalid Email Checker – Verify Email for Free">
        <meta name="twitter:description" content="Instantly check if an email is disposable, invalid, or spam-trap. Use Bouncee’s free email address verifier to ensure deliverability and protect your sending reputation.">
        <meta name="twitter:image" content="https://bouncee.net/assets/img/logo.png">
    @endpush
    @push('styles')
        <link rel="stylesheet" href="{{ asset('verify/single/css/style.css') }}">
        <link rel="shortcut icon" href="assets/bouncee-logo.png" type="image/png">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('verify/single/script.js') }}" type="text/javascript"></script>
    @endpush

    @php
        $oldVerificationData = $headerData['oldVerificationData'] ?? null;
    @endphp
    <section class="single-area" id="single--verification">
        <div class="container1">
            <div class="validate-heading">
                <h2>Validate an Email Address</h2>
            </div>
            <div class="row mx-0">
                <div class="col-md-6 col-sm-12 grid-col">
                    <div class="single-card">
                        <div class="card-title card-row">
                            <i class="fa-light fa-envelope"></i>
                            <div class="title-content">
                                <h2 class="title-head">Single Check</h2>
                                <p>Perform single email verification with deep analysis result.</p>
                            </div>
                        </div>
                        <h3 class="single-card-head">Enter email to check
                            <form method="POST" accept-charset="utf-8" class="form-inner" id='signinForm'
                                action="{{ route('check-email') }}">
                                @csrf
                                {{-- <input type="text" class="emailCheck hover-border mt-25 {{$errors->has('first_name') ? 'validation-block-error':''}}" placeholder="Enter first name" name="first_name" value={{old('first_name')}} >
                            <span class="{{ $errors->has('first_name') ? 'validation-span-error':''}}" id="fNameError">
                                @error('first_name')
                                    {{ $message }}  
                                @enderror
                            </span>
                            <input type="text" class="emailCheck hover-border {{$errors->has('last_name') ? 'validation-block-error':''}}" placeholder="Enter last name" name="last_name" value="{{old('last_name')}}" >
                            <span class="{{ $errors->has('last_name') ? 'validation-span-error':''}}" id="lNameError">
                                @error('last_name')
                                    {{ $message }}  
                                @enderror
                            </span> --}}
                                <input type="email"
                                    class="emailCheck hover-border {{ $errors->has('domain') ? 'validation-block-error' : '' }}"
                                    placeholder="Email to check" name="domain" value="{{ old('domain') }}">
                                <span class="{{ $errors->has('domain') ? 'validation-span-error' : '' }}" id="domainError">
                                    @error('domain')
                                        {{ $message }}
                                    @enderror
                                </span>
                                {{-- <span class="Buttonloader"></span> --}}
                                <button class="btn submit-verification-btn" id="CheckButon">Check </button>
                            </form>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 grid-col">
                    <div class="single-card">
                        <h3 class="right-card-head">Analysis Results</h3>
                        <div class="outer-layer">
                            <div class="inner-layer">
                                @php
                                    
                                    $data   = session('validEmails')
                                @endphp
                                @if (!empty($data))
                                    @php 
                                        $status    = $data['status'];
                                        $statusChk = strtolower($data['status']);
                                        $email  = $data['email']; 
                                        $color  = '';
                                        if($statusChk =='deliverable') {$color='#28A745';}
                                        else if($statusChk =='undeliverable') $color='#DC3545';
                                        else if($statusChk =='unknown') $color='#FFA500';
                                        else if($statusChk =='accept all') $color='#FFC107';
                                    @endphp
                                    <div class="correct-email" style="border: 2px solid {{$color}}">
                                        <div class="col-md-8"> 
                                            <h2> <i class="fa-light fa-check"></i>  {{ $email }}</h2>
                                        </div>
                                        <div class="col-md-3 col-offset-1 px-0"> 
                                            <div class="status--div" style="background-color: {{$color}}">{{ $status }}</div>
                                        </div> 
                                    </div>  
                                    @else
                                    <div class="no-content-parent">
                                        <img class="no-content" id="analImage"
                                            src="{{ asset('verify/single/image/1.png') }}" alt="no content" srcset="">
                                    </div>
                                @endif
                                <div class="no-content-parent">
                                    <div class="analLoader" id="AnalLoader"> </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
            <div class="row mx-0">
                @if ($oldVerificationData != null && !empty($oldVerificationData))
                    {{-- <h1>Single Verification History</h1> --}}
                    <div class="col-md-12 mt-5">
                        <div class="history-card">
                            <div class="outer-layer">
                                <div class="inner-layer"> 
                                    <h3 class="right-card-head">Single Verification History</h3>
                                    @foreach ($headerData['oldVerificationData'] as $data)
                                        @php
                                             $status    = $data['status'];
                                             $statusChk = strtolower($data['status']);
                                            $email = $data['email'];
                                            $color  = ''; 
                                            if($statusChk =='deliverable') {$color='#28A745';}
                                            else if($statusChk =='undeliverable') $color='#DC3545';
                                            else if($statusChk =='unknown') $color='#FFA500';
                                            else if($statusChk =='accept all') $color='#FFC107';
                                        @endphp
                                        <style>
                                            .correct-email .fa-light::before{
                                                font-size: 14px;
                                            }
                                        </style>
                                        <div class="correct-email" style="font-size:14px;color:{{$color}};border: 2px solid {{$color}}">
                                            <div class="col-md-8">
                                                <h2 style="color:{{$color}}"> <i class="fa-light fa-check" style="color:{{$color}}"></i> {{ $email }}</h2>
                                            </div>
                                            <div class="col-md-3 col-offset-1 px-0">
                                                <div
                                                    class="status--div"  style="background-color: {{$color}}">
                                                    {{ $status }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>

                        </div>
                    </div>
                @endif

            </div>

        </div>
    </section>
    <section class="mt-25"></section>
    @include('support')

@endsection
