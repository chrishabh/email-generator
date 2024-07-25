<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
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
</head>

<body>

  <!-- Header Area wrapper Starts -->
  <header id="header-wrap">
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-md bg-inverse fixed-top scrolling-navbar">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <a href="index.php" class="navbar-brand"><img src="assets/logo.png" alt=""></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <i class="lni-menu"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto w-100 justify-content-end clearfix">
            <li class="nav-item active">
              <a class="nav-link" href="#hero-area">
                Home
              </a>
            </li>
            @if($headerData['whichPageRequest'] != 'singlePage')
            <li class="nav-item">
              <a class="nav-link" href="#services">
                Services
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#team">
                Team
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#pricing">
                Pricing
              </a>
            </li>
            @endif
            <li class="nav-item">
              <a class="nav-link" href="/single-verification">
                Single Verification
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/bulk-verification">
                Bulk Verification
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#testimonial">
                Sign In
              </a>
            </li>
            <li class="nav-item">
              <a rel="nofollow" href="/signup" class="btn btn-home-common py-2">Sign Up</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Navbar End -->

  </header>
  <!-- Header Area wrapper End -->