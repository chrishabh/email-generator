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
                <h1 class="section-title">🚀 About Bouncee</h1>
              </div>
              <div class="content">
                <p style="color: black; text-align:center">
                At Bouncee, we help businesses supercharge their email marketing by keeping their lists accurate, verified, and 100% up to date.
                With powerful technology and years of expertise, our platform ensures your emails reach real inboxes — not spam folders.
                </p>
                <p style="color: black; text-align:center">
                We make it simple to boost deliverability, protect your sender reputation, and get the most from every campaign you send.
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-xs-12 wow fadeInRight" data-wow-delay="0.3s">
          <img class="img-fluid" src="assets/about/img-1.png" alt="">
        </div>

        <hr class="my-5">

            <!-- MISSION -->
            <div class="row text-center mb-5 wow fadeInUp" data-wow-delay="0.2s">
                <div class="col-md-8 mx-auto">
                    <h3 class="fw-semibold mb-3">🎯 Our Mission</h3>
                    <p class="text-muted">
                        To help businesses build stronger connections by delivering clean, verified, and high-quality email lists —
                        ensuring every message counts. We’re on a mission to make email marketing smarter, safer, and more effective
                        for everyone.
                    </p>
                </div>
            </div>

            

            <!-- VISION -->
            <div class="row text-center mb-5 wow fadeInUp" data-wow-delay="0.3s">
                <div class="col-md-8 mx-auto">
                    <h3 class="fw-semibold mb-3">🌍 Our Vision</h3>
                    <p class="text-muted">
                        To be the world’s most trusted email verification and data hygiene platform, setting new benchmarks for accuracy,
                        speed, and reliability. We envision a future where every email helps brands grow — not bounce.
                    </p>
                </div>
            </div>

           

            <!-- TEAM -->
            <div class="row text-center mb-5 wow fadeInUp" data-wow-delay="0.4s">
                <div class="col-md-8 mx-auto">
                    <h3 class="fw-semibold mb-3">👩‍💻 Our Team</h3>
                    <p class="text-muted">
                        Behind Bouncee is a passionate group of email deliverability experts, software engineers, and data scientists
                        committed to precision and performance. We love solving real-world marketing problems with smart technology —
                        helping businesses send confidently and connect meaningfully.
                    </p>
                </div>
            </div>

           

            <!-- SOFTWARE -->
            <div class="row text-center wow fadeInUp" data-wow-delay="0.5s">
                <div class="col-md-10 mx-auto">
                    <h3 class="fw-semibold mb-3">⚙️ Our Software</h3>
                    <p class="text-muted">
                        The Bouncee Email Verification Engine combines AI-driven algorithms, real-time validation APIs, and machine learning
                        models to clean your lists with unmatched accuracy.
                    </p>

                    <div class="row justify-content-center mt-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm p-4 bg-white rounded-4">
                                <h5 class="fw-semibold mb-3 text-primary">Key Features</h5>
                                <ul class="list-unstyled text-start text-muted mb-0">
                                    <li>✅ Real-Time Email Verification API</li>
                                    <li>✅ Bulk List Cleaning</li>
                                    <li>✅ Spam Trap & Disposable Email Detection</li>
                                    <li>✅ Bounce Rate & Deliverability Reports</li>
                                    <li>✅ Easy Integration with Major Platforms</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <p class="mt-4 text-muted">
                        With Bouncee, you get speed, security, and accuracy — all in one seamless solution.
                    </p>
                </div>
        </div>
      </div>
    </div>
  </div>
  @endsection
