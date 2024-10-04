@extends('layout.main')

@push('title')
    <title>Settings | Bouncee</title>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('user/settings/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/bouncee-logo.png') }}" type="image/png">
    <script src="{{ asset('user/settings/settings.js') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/wow.js"></script>
    <script src="js/jquery.nav.js"></script>
    <!-- <script src="js/scrolling-nav.js"></script> -->
    <script src="js/jquery.easing.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/waypoints.min.js"></script>
    <script src="js/main.js"></script>
    <!-- <script src="js/slim.min.js"></script> -->
    <script src="js/three.min.js"></script>
   
@endpush

@php
    if(!empty($userData))
        $userData = $userData->toArray();

    $name = '';
    if (!empty($userData) && isset($userData['name'])) {
        $fullName = $userData['name'];
        $nameParts = explode(' ', $fullName);
        
        if (count($nameParts) < 2) {
            $firstName = $nameParts[0];
            $name = strtoupper($firstName);
        } else {
            $firstName = $nameParts[0];
            $lastName = $nameParts[count($nameParts) - 1];
            $firstCapitalLetter = strtoupper($firstName[0]);
            $lastCapitalLetter = strtoupper($lastName[0]);
            $name = $firstCapitalLetter . $lastCapitalLetter;
        }
    }
@endphp

@section('main-section')
  <!-- Sidebar -->
  <meta name="setting-page-token" content="{{ csrf_token() }}">
  <meta name="delete-button-token" content="{{ csrf_token() }}">
  <div class="row">
    <div class="sidebar" id="black-sidebar">
        <ul class="dashboard-listing">
            <li><a class="active" id="dashboard" onclick="renderHtml(event,this)"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a  id="setting" onclick="renderHtml(event,this)"><i class="fas fa-cogs"></i><span>Settings</span></a></li>
            <li><a  id="messages" onclick="renderHtml(event,this)"><i class="fas fa-user"></i><span>Messages</span></a></li>
            {{-- <li><a href="#"><i class="fas fa-question-circle"></i><span>Help</span></a></li> --}}
        </ul>
    </div>
    
    <!-- Main Content -->
    <section id="admin-setting-page" class="container1">
        <div id="setting-section"></div>
        {{-- <h1>Overall User Credits Report</h1> --}}

        <!-- Chart Container -->
        <div id="dashboard-section">
            <div class="row mx-0">
                 
                <div class="col-md-6" id="left-section-of-chart">
                    <h1>Total Credit Score</h1>
                    <canvas id="overallCreditsChart" width="400" height="400"></canvas>
                </div>
                <div class="col-md-6" id="right-section-of-chart">
                    <h1>Total Available Credit Score of API</h1>
                    <canvas id="overallCreditsAdminChart" width="400" height="400"></canvas>
                   
                </div>
            </div> 
        </div>

        <div id="messages-section"></div>
    
    </section>
  </div>
    
     
  <div id="preloader">
    <div class="loader" id="loader-1"></div>
</div>

@endsection
