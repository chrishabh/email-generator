<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- @stack('title') --}}
        <title>bouncee</title>
          @stack('title')
    {{-- @endpush --}}
    
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset('assets/bouncee-logo.png') }}" type="image/png">
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
    @stack('styles')

</head>

<body>
    @php
    // Retrieve the logged-in user's name
    $fullName = Auth::user()->name;
    $role = Auth::user()->role;

    // Check if the name contains at least two parts
    $nameParts = explode(' ', $fullName);
    $name ='';
    if (count($nameParts) < 2) {
        $firstName = $fullName[0];
        $name      = strtoupper($firstName); 
    }else{
        $firstName = $nameParts[0];
        $lastName = $nameParts[count($nameParts) - 1]; 
        // Get the first and last capital letters
        $firstCapitalLetter = strtoupper($firstName[0]);
        $lastCapitalLetter = strtoupper($lastName[0]); 
        $name              = $firstCapitalLetter.''.$lastCapitalLetter;
    }


    $creditClass ='credit-color-50L';
    $creditPoint=0;
    if (isset($headerData)){
        $creditPoint = $headerData['creditPoint'];
        if($creditPoint>=100)  $creditClass ='credit-color-100p';  
        else if($creditPoint<50) $creditClass ='credit-color-50L'; 
        else $creditClass = 'credit-color-100M'; 
    }  
    @endphp
    <!-- Header Area wrapper Starts -->
    <div id="mobileMenu" class="mobile-menu">
        <ul class="main-menu"> 
            <li class="current"><a href="/single">Single Verification</a></li> 
            <li class="current"><a href="/lead-finder">Lead Finder</a></li> 
            <li class=""><a href="/bulk">Bulk Verifications</a></li>
            <li><a href="/pricing">Buy Credits</a></li> 
            <li><a href="/profile">Profile</a></li>
            <li><a href="/payment-history">Payment History</a></li>
            @if($role == 'admin')
                <li><a href="/settings">Settings</a></li>        
            @endif
            <li><a href="/logout" id="out">Sign Out</a></li>
        </ul>
        <div class="credit-info">
            Credit Balance
                   
            <div class="credit-info--tag {{ $creditClass }}"> 
                {{$creditPoint}}  
                {{-- <div class="buy-option">Buy More Credits</div> --}}
            </div>
        </div>
        <a href="" class="icon-notification btn-notification active">
        </a> 
    </div>
    <header class="" id="header1">
        <div class="container1">
            <div class="mainheader">
                <div class="mainheader--left">
                    <a href="" class="logo">
                        <img src="{{ asset('/assets/logo.png') }}" alt="">
                    </a>
                    <ul class="main-menu">
                        <li class="current"><a href="/single">Single Verification</a></li>
                        <li class="current"><a href="/lead-finder">Lead Finder</a></li>
                        <li class=""><a href="/bulk">Bulk Verifications</a></li>
                        <li><a href="/pricing">Buy Credits</a></li>
                    </ul>
                </div>
                <div class="mainheader--right">
                    <div class="credit-info">
                        <div class="credit--row">
                            <span class="creditBalnce"> Credit Balance</span>
                            <div class="credit-info--tag {{ $creditClass }}" id="creditPoint"> 
                                {{$creditPoint}}  
                            </div>
                        </div>
                        
                        <div class="buy-option"><a href="/pricing">Buy now</a></div>
                    </div>
                    <div class="user-info">
                        <div class="avatar">
                            <span class="avatar-text">{{$name}}</span>
                        </div>
                        <div class="header-dropdown">
                            <div id="myDropdown" class="dropdown-content">
                                <a href="/profile">Profile</a>
                                <a href="/payment-history">Payment History</a>
                                @if($role == 'admin')
                                    <a href="/settings">Settings</a>
                                @endif
                                <!-- <div class="dropdown-divider" id="dive"></div> -->
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
