@extends('layout.main')
@php
    $headerData = array();
    $headerData['whichPageRequest'] ='singlePage';
@endphp
@section('main-section')
    @push('title')
        <title>Bulk Email Email Verification | bouncee</title>
    @endpush
    @push('styles')
        <link rel="stylesheet" href="singleEmailVerification-assets/css/index.css">
        <link rel="stylesheet" href="BulkEmailVerification-assets/css/bulkIndex.css">
    @endpush
    @section('specificScript')
    <script src="singleEmailVerification-assets/js/singleVerification.js" type="text/javascript"></script>
    @endsection
    <section class="hero-section ani_has_move_parallax header-ver-1 bulkEmail">
        <div class="right-shape-area">
            <div class="group-shape ani_move_parallax_el">
                <span class="shape-1 shape"><img src="singleEmailVerification-assets/img/line.svg" alt></span>
            </div>
        </div>
        <div class="container " style="position:relative;">
            <div class="shape shapeRt ani-rotation wow fadeInUp"><img src="singleEmailVerification-assets/img/s1.svg"
                    alt="bouncee">
            </div>
            <div class="shape shapeRt2 ani-rotation wow fadeInUp"><img src="singleEmailVerification-assets/img/s2.svg" alt>
            </div>
            <div class="topHeading alt">
                <div class="row">
                    <div class="col-lg-7 text-center">
                        <h1 class="wow fadeInUp head-main" style="text-align: center;">Bulk Email Verification  <br><span>For an
                            effective email campaign </span></h1>
                        <p class="wow fadeInUp para">
                        Bulk email verification ensures large email lists are accurate and active, improving campaign efficiency. By filtering out invalid and risky addresses, it reduces bounce rates, enhances deliverability, and protects your sender reputation. This powerful tool helps your messages reach their intended audience, optimizing your marketing efforts and budget.
                        </p>
                        <a href='/signup' class="btn btn-success wow fadeInUp signup-btn" data-wow-duration="1s"
                            data-wow-delay="0.7s">Sign Up Free</a>
                        {{-- <div class="signup-container">
                            <form class="signup-form">
                                <input type="email" class="form-control" placeholder="Email address"
                                    aria-label="Enter Email address">
                                <button type="submit" class="btn">Validate Email</button>
                            </form>
                        </div> --}}
                    </div>
                    <div class="col-lg-5 wow fadeInRight text-center" data-wow-delay="0.7s">
                        <div class="p-0 text-center">
                            <video loop="" muted="" autoplay="" class="banner-video">
                                <source src="singleEmailVerification-assets/img/verify.mp4" type="video/mp4">
                            </video>
                            {{-- <img src="singleEmailVerification-assets/img/single-email.png" alt="Single email verification "> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="Htw Bulk">
        <div class="container"> 
            <h2 class="hiwB text-center pb-4  wow fadeInRight">How it works</h2>
            <div class="row justify-content-end">
                <div class="col-lg-5 wow fadeInRight"  data-wow-delay="0.7s">
                    <div style="padding: 120px 0 0 0;" >
                        <div class="wow fadeInRight para" style="line-height: 37px;font-size: 21px;">
                            <p><b>1.</b> Upload a file (CSV, TXT, etc.) containing the email addresses.</p>
                            <p><b>2.</b> The system performs multiple checks on each email address.
                            </p>
                            <p><b>3.</b> Receive a file with the verification results, indicating which emails are valid, invalid, or risky.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 wow fadeInLeft">
                    <div class="image-H">
                        <img src="BulkEmailVerification-assets/img/dash-screen.png" alt="Enter Email">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section Start -->
    <section id="features" class="section-padding">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Awesome Features</h2>
                <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="content-left">
                        <div class="box-item wow fadeInLeft" data-wow-delay="0.2s">
                            <span class="icon">
                                <i class="lni-timer"></i>
                            </span>
                            <div class="text">
                                <h4>High Volume Processing </h4>
                                <p style="color: black;">Capable of handling large email lists.</p>
                            </div>
                        </div>
                        <div class="box-item wow fadeInLeft" data-wow-delay="0.4s">
                            <span class="icon">
                                <i class="lni-check-mark-circle"></i>
                            </span>
                            <div class="text">
                                <h4>Detailed Validation Results</h4>
                                <p style="color: black;"> Comprehensive reports on the verification status of each email.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="show-box wow fadeInUp" data-wow-delay="0.2s">
                        <img style="margin-top: -26px;" src="assets/feature/intro-mobile.png" alt="">
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="content-right">
                        <div class="box-item wow fadeInLeft" data-wow-delay="0.9s">
                            <span class="icon">
                                <i class="lni-display"></i>
                            </span>
                            <div class="text">
                                <h4>Secure Upload</h4>
                                <p style="color: black;">Ensure the security and privacy of your data.</p>
                            </div>
                        </div>
                        <div class="box-item wow fadeInRight" data-wow-delay="0.4s">
                            <span class="icon">
                                <i class="lni-layers"></i>
                            </span>
                            <div class="text">
                                <h4>99% Accuracy Rate</h4>
                                <p style="color: black;">Rely on a highly accurate verification system that ensures your
                                    email lists are
                                    free from invalid addresses, enhancing deliverability and campaign success rates.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features Section End -->

    <x-price />

    <!-- Testimonial Section Start -->
    <x-testimonial />
    <!-- Testimonial Section End -->
@endsection
