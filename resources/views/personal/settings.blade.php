@extends('layout.main')

@push('title')
    <title>Settings | Bouncee</title>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('user/settings/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/bouncee-logo.png') }}" type="image/png">
    <script src="{{ asset('user/settings/index.js') }}" type="text/javascript"></script>
   
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
  <div class="sidebar">
            <ul>
                <li><a href="#" class="active"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
                <li><a href="/payment-history"><i class="fas fa-history"></i><span>Payment History</span></a></li>
                <li><a href="#"><i class="fas fa-cogs"></i><span>Settings</span></a></li>
                <li><a href="#"><i class="fas fa-user"></i><span>Messages</span></a></li>
                <li><a href="#"><i class="fas fa-question-circle"></i><span>Help</span></a></li>
            </ul>
        </div>


        <!-- Main Content -->
        <div class="main-content">
            <h1>Dashboard</h1>
            <p>Welcome to your dashboard. Navigate through the sidebar to explore different sections.</p>
        </div>

@endsection
