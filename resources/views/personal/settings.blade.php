@extends('layout.main')

@push('title')
    <title>Settings | Bouncee</title>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('user/settings/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/bouncee-logo.png') }}" type="image/png">
    <script src="{{ asset('user/settings/settings.js') }}" type="text/javascript"></script>
   
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
  <div class="row">
    <div class="sidebar">
        <ul>
            <li><a href="#" class="active"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="/payment-history"><i class="fas fa-history"></i><span>Payment History</span></a></li>
            <li><a  id="setting" onclick="renderHtml(event,this)"><i class="fas fa-cogs"></i><span>Settings</span></a></li>
            <li><a href="#"><i class="fas fa-user"></i><span>Messages</span></a></li>
            <li><a href="#"><i class="fas fa-question-circle"></i><span>Help</span></a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <section id="admin-setting-page" class="container1">
        <div id="setting-section">
            <h1 class="user-heading">Users Table</h1>
            <table class="table table-hover table-bordered">
                <thead class="table-head">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Available Credits</th>
                        <th scope="col">Reset Password</th>
                        <th scope="col">delete User Ac</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Rishabh singh</td>
                        <td>Rishabh.singh@yopmail.com</td>
                        <td>122 -credits</td>
                        <td class="text-center"><i class="fas fa-key fa-redo-alt"></i></td>
                        <td class="text-center"><i class="fa-solid fa-trash"></td>
                    </tr>
                    <tr>
                        <th scope="row">1</th>
                        <td>Rishabh singh</td>
                        <td>Rishabh.singh@yopmail.com</td>
                        <td>122 -credits</td>
                        <td class="text-center"><i class="fas fa-key fa-redo-alt"></i></td>
                        <td class="text-center"><i class="fa-solid fa-trash"></td>
                    </tr>
                    <tr>
                        <th scope="row">1</th>
                        <td>Rishabh singh</td>
                        <td>Rishabh.singh@yopmail.com</td>
                        <td>122 -credits</td>
                        <td class="text-center"><i class="fas fa-key fa-redo-alt"></i></td>
                        <td class="text-center"><i class="fa-solid fa-trash"></td>
                    </tr>
                </tbody>
            </table>
        </div>
         
    </section>
  </div>
    
     
     

@endsection
