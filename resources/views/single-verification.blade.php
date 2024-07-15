@extends('layout.main')

@section('main-section')
    @push('title')
        <title>Single Email Verification | Bouncee</title>
    @endpush
    @push('styles')
        <link rel="stylesheet" href="singleEmailVerification-assets/css/index.css">
    @endpush
    <section class="hero-section ani_has_move_parallax header-ver-1">
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
                    <div class="col-lg-7">
                        <h1 class="wow fadeInUp head-main">Single email verification <br> <span>your email checking best
                                friend</span></h1>
                        <p class="wow fadeInUp para">In today’s digital landscape, maintaining the integrity of your email
                            list is crucial for successful communication and marketing campaigns. Single email verification
                            is a vital tool that helps ensure the email addresses you have are valid, active, and ready to
                            receive your messages. This process involves verifying one email address at a time to determine
                            its authenticity and deliverability.
                            <br>By utilizing single email verification, you can significantly reduce bounce rates, protect
                            your
                            sender reputation, and improve overall email deliverability. Whether you’re sending out
                            newsletters, promotional offers, or important updates, having a verified email address ensures
                            your message reaches its intended recipient. This not only enhances your communication efforts
                            but also optimizes your resources by avoiding the pitfalls of sending emails to invalid or
                            inactive addresses. Embrace single email verification to maintain a clean, efficient, and
                            effective email list.
                        </p>
                        <a href class="btn btn-success wow fadeInUp signup-btn" data-wow-duration="1s"
                            data-wow-delay="0.7s">Sign up</a>
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
                            {{-- <img src="singleEmailVerification-assets/img/single-email.png" alt="Single email verification by MailTester.com"> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="Htw">
        <div class="container">
            <div class="row justify-content-end align-items-center">
                <div class="col-lg-2 wow fadeInLeft enter-email">
                    <div class="Htw-img">
                        <img src="singleEmailVerification-assets/img/enter-email.png" alt="Enter Email">
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="Htw-main">
                        <div class="wow fadeInRight">
                            <h2 class="hiw">How it works</h2>
                            <p><span>1.</span> Enter the email address you want to verify.
                            </p>
                            <p class="odd"><span>2.</span>The system performs various checks, including syntax, domain,
                                and mailbox checks. </p>
                            <p><span>3.</span>The verification results are displayed, indicating whether the email is valid,
                                invalid, or risky.</p>
                        </div>
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
                                <h4>Real-time Email Verification</h4>
                                <p style="color: black;">Instant results with a high accuracy rate.</p>
                            </div>
                        </div>
                        <div class="box-item wow fadeInLeft" data-wow-delay="0.4s">
                            <span class="icon">
                                <i class="lni-check-mark-circle"></i>
                            </span>
                            <div class="text">
                                <h4>Detailed Validation Results</h4>
                                <p style="color: black;"> Comprehensive reports on the verification status.</p>
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
                                <h4>Easy-To-Use Interface</h4>
                                <p style="color: black;">Simple interface for quick and easy verification.</p>
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
