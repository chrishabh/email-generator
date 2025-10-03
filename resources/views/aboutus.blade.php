@extends('layout.main')


@php
    $headerData = array();
    $headerData['whichPageRequest'] ='aboutus';
@endphp

@section('main-section')
    @push('title')
        <title>About Us | bouncee</title>
    @endpush

<div class="about-area section-padding bg-gray">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-12 col-xs-12 info">
          <div class="about-wrapper wow fadeInLeft" data-wow-delay="0.3s">
            <div>
              <div class="site-heading">
                <p class="mb-3" style="color: black; text-align:center">Welcome to Your Trusted Email Verification Partner</p>
                <h2 class="section-title">About Us</h2>
              </div>
              <div class="content">
                <p style="color: black; text-align:center">
                  At bouncee, we are dedicated to helping businesses enhance their email marketing efforts by ensuring
                  their email lists are accurate, valid, and up-to-date. With years of experience and cutting-edge
                  technology, we provide top-notch email verification services that help our clients protect their
                  sender reputation, improve deliverability, and maximize their marketing ROI.
                </p>
                <!-- <a href="#" class="btn btn-common mt-3">Read More</a> -->
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-xs-12 wow fadeInRight" data-wow-delay="0.3s">
          <img class="img-fluid" src="assets/about/img-1.png" alt="">
        </div>
      </div>
    </div>
  </div>
  @endsection
