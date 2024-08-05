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
    <title>Bouncee | Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
    background-image: url(../assets/dark-payment-bg.png);
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    min-height: 400px;
    position: relative;
    overflow: hidden;
    padding: 10px 0 10px;
">
<div class="invoice-box" style="
    background: whitesmoke;">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                            <a href="index.php" class="navbar-brand"><img src="assets/Razorpay.svg" alt=""></a>
                            </td>
                            <td>
                                Order #: {{$orderId}}<br>
                                Created: {{$created_at}}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                            {{$prefill_name}}<br>
                            {{$prefill_email}}
                            </td>
                            <td>
                                Company Name<br>
                                {{$company_name}}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Item</td>
                <td>Price</td>
            </tr>
           
                <tr class="item">
                    <td>{{number_format($credits)}} Verifications</td>
                    <td>${{ number_format($amount/100, 2) }}</td>
                </tr>
           
            <tr class="total">
                <td></td>
                <td>Total: ${{ number_format($amount/100, 2) }}</td>
            </tr>
            
        </table>
        <form action="/handle-payment" method="POST">
                @csrf
                <script
                    src="https://checkout.razorpay.com/v1/checkout.js"
                    data-key="{{ config('razorpay.key') }}"
                    data-amount="{{$amount}}"
                    data-currency="{{$currency}}"
                    data-order_id="{{$orderId}}"
                    data-buttontext="Pay with Razorpay"
                    data-name="{{$company_name}}"
                    data-description="{{$description}}"
                    data-prefill.name="{{$prefill_name}}"
                    data-prefill.email="{{$prefill_email}}"
                    data-theme.color="#F37254"
                ></script>
            </form>
    </div>
</body>
</html>
