<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- @stack('title') --}}
        <title>Bouncee</title>
    {{-- @endpush --}}
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('bouncee-logo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Icon -->
    <link rel="stylesheet" href="{{ asset('fonts/line-icons.css') }}">
    <!-- Owl carousel -->
    <!-- Animate -->
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <!-- Main Style -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <!-- Responsive Style -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/header1/style.css') }}">    
    <script src="{{ asset('assets/header1/index.js') }}" type="text/javascript"></script>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    @stack('styles')

</head>

<body>
    @php
    // Retrieve the logged-in user's name
    $fullName = Auth::user()->name;

    // Check if the name contains at least two parts
    $nameParts = explode(' ', $fullName);
    $name ='';
    if (count($nameParts) < 2) {
        $firstName = $nameParts[0];
        $name      = strtoupper($firstName); 
    }else{
        $firstName = $nameParts[0];
        $lastName = $nameParts[count($nameParts) - 1]; 
        // Get the first and last capital letters
        $firstCapitalLetter = strtoupper($firstName[0]);
        $lastCapitalLetter = strtoupper($lastName[strlen($lastName) - 1]); 
        $name              = $firstCapitalLetter.''.$lastCapitalLetter;
    }
    @endphp
    <!-- Header Area wrapper Starts -->
    <div id="mobileMenu" class="mobile-menu">
        <ul class="main-menu"> 
            <li class="current"><a href="/single">Single Verification</a></li> 
            <li class=""><a href="/bulk">List Verification</a></li>
            <li><a href="/pricing">Pricing</a></li> 
        </ul>
        <div class="credit-info">
            Credit Balance
            <div class="credit-info--tag"> 
                @if (isset($headerData) && $headerData['creditPoint'])
                  {{ $headerData['creditPoint']}}      
                @endif
            </div>
        </div>
        <a href="" class="icon-notification btn-notification active">
        </a> 
    </div>
    <header class="" id="header1">
        <div class="container1">
            <div class="mainheader">
                <div class="mainheader--left">
                    <a href="/" class="logo">
                        <img src="{{ asset('/assets/logo.png') }}" alt="">
                    </a>
                    <ul class="main-menu">
                        <li class="current"><a href="/single">Single Verification</a></li>
                        <li class=""><a href="/bulk">List Verification</a></li>
                        <li><a href="/pricing">Pricing</a></li>
                    </ul>
                </div>
                <div class="mainheader--right">
                    <div class="credit-info">
                        <span class="creditBalnce"> Credit Balance</span>
                        <div class="credit-info--tag" id="creditPoint"> 
                            @if (isset($headerData['creditPoint']))
                                {{ $headerData['creditPoint']}}      
                            @endif
                        </div>
                    </div>
                    <div class="user-info">
                        <div class="avatar">
                            <span class="avatar-text">{{$name}}</span>
                        </div>
                        <div class="header-dropdown">
                            <div id="myDropdown" class="dropdown-content">
                                <a href="/setting">Profile</a>
                                <a href="/setting">Setting</a>
                                <div class="dropdown-divider" id="dive"></div>
                                <a href="/logout" id="out">Sign Out</a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="hamburger hamburger--elastic fl" id="openMobileMenu">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header Area wrapper End -->
