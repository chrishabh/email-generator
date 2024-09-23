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
    <div class="sidebar">
        <ul>
            <li><a class="active" id="dashboard" onclick="renderHtml(event,this)"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="/payment-history"><i class="fas fa-history"></i><span>Payment History</span></a></li>
            <li><a  id="setting" onclick="renderHtml(event,this)"><i class="fas fa-cogs"></i><span>Settings</span></a></li>
            <li><a href="#"><i class="fas fa-user"></i><span>Messages</span></a></li>
            <li><a href="#"><i class="fas fa-question-circle"></i><span>Help</span></a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <section id="admin-setting-page" class="container1">
        <div id="setting-section"></div>
        {{-- <h1>Overall User Credits Report</h1> --}}

        <!-- Chart Container -->
        <div id="dashboard-section">
            <div class="row">
                <h1>Total Credit Score</h1>
                <div class="col-md-6" id="left-section-of-chart">
                    <canvas id="overallCreditsChart" width="400" height="400"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="overallCreditsChart" width="400" height="400"></canvas>
                </div>
            </div> 
        </div>
    
    </section>
  </div>
    
     
     

@endsection
