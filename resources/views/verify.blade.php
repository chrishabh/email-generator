<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @stack('title')

    @stack('styles')
    <link rel="shortcut icon" href="assets/bouncee-logo.png" type="image/png">
 <!-- Bootstrap CSS -->
 <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Icon -->
    <link rel="stylesheet" href="fonts/line-icons.css">
    <!-- Owl carousel -->
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.css">

    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/nivo-lightbox.css">
    <!-- Animate -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- Main Style -->
    <link rel="stylesheet" href="css/main.css">
    <!-- Responsive Style -->
    <link rel="stylesheet" href="css/responsive.css">

    @stack('styles')
    <title>Verification | Bouncee</title>
</head>
<header id="header-wrap">
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-md bg-inverse fixed-top scrolling-navbar" style="
    background: whitesmoke;">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <a href="index.php" class="navbar-brand"><img src="assets/logo.png" alt=""></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="lni-menu"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav mr-auto w-100 justify-content-end clearfix">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                {{auth()->user()->name}}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a rel="nofollow" href="/logout" class="btn btn-home-common py-2">Log out</a>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->

    </header>
<body style="
    background-image: url(../assets/verification-screen-bg.png);
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    min-height: 550px;
    position: relative;
    overflow: hidden;
    padding: 10px 0 10px;
">

<div class="invoice-box" style="
    background: whitesmoke;">
        <div class="verification-header text-center">
            <h2>Enter Verification Code</h2>
            <p>Please enter the verification code sent to your email.</p>
        </div>
        @if (session()->has('success'))
            <div class="alert alert-success">
            {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('verification.code') }}">
            @csrf
            <div class="form-group">
                <label for="verification_code">Verification Code</label>
                <input style="width: 20em;" type="text" class="form-control" id="verification_code" name="verification_code" required>
            </div>
            <button type="submit" class="btn btn-primary verification-button">Verify Code</button>
        </form>
    </div>
</body>
</html>
