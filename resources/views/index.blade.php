@extends('layout.main')
  <!-- Services Section Start -->
  @php
    $headerData = array();
    $headerData['whichPageRequest'] ='mainPage';
@endphp
@section('main-section')
  @push('title')
    <title>bouncee</title>
  @endpush
    <!-- Hero Area Start -->
    <div id="hero-area" class="hero-area-bg">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">
            <div class="contents">
              <h2 class="head-title">Email Verification Tool<br>Effortless, Swift, & Precise</h2>
              <p style="color: black; text-align:center">Over 1 Million users trust bouncee for their real-time email validation and email cleaning services. Prevent bounce backs, disposable addresses, spam traps, and deactivated emails from harming your sending reputation and wasting your resources.
              Utilize a bulk email verification tool to ensure the quality and accuracy of your email list.</p>
              @if(!auth()->check())
                <div class="header-button">
                  <a rel="nofollow" href="/signup" class="btn btn-home-common">Start For Free</a>
                </div>
              @endif
            </div>
          </div>
          <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
            <div class="intro-img">
              <img class="img-fluid" src="assets/intro-mobile.png" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Hero Area End -->
  <section id="services" class="section-padding">
    <div class="container">
      <div class="section-header text-center">
        <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Our Services</h2>
        <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
      </div>
      <div class="row">
        <!-- Services item -->
        <div class="col-md-6 col-lg-6 col-xs-12">
          <div class="services-item wow fadeInRight" data-wow-delay="0.3s">
            <div class="icon">
              <i class="lni-envelope"></i>
            </div>
            <div class="services-content">
              <h3><a href="#">Single Email Verification</a></h3>
              <p style="color: black;">Our single email verification service is designed for those who need to verify
                individual email addresses on-the-go. Whether you're adding new contacts to your list or checking the
                validity of an email address before sending an important message, our tool provides instant and accurate
                results. Simply enter the email address, and our system will verify its validity in real-time.</p>
            </div>
          </div>
        </div>
        <!-- Services item -->
        <div class="col-md-6 col-lg-6 col-xs-12">
          <div class="services-item wow fadeInRight" data-wow-delay="0.6s">
            <div class="icon">
              <i class="lni-envelope"></i>
            </div>
            <div class="services-content">
              <h3><a href="#">Bulk Email Verification</a></h3>
              <p style="color: black;">Our bulk email verification service is ideal for businesses looking to clean and
                verify large email lists quickly and efficiently. By uploading your entire email list to our secure
                platform, you can eliminate invalid, bounce, disposable, spam-trap, and deactivated emails, ensuring
                that your marketing campaigns reach their intended audience.</p>
            </div>
          </div>
        </div>
        <!-- Services item -->
        <!-- <div class="col-md-6 col-lg-4 col-xs-12">
            <div class="services-item wow fadeInRight" data-wow-delay="0.9s">
              <div class="icon">
                <i class="lni-users"></i>
              </div>
              <div class="services-content">
                <h3><a href="#">Easy To Customize</a></h3>
                <p>Ut maximus enim dolor. Aenean auctor risus eget tincidunt lobortis. Donec tincidunt bibendum gravida. </p>
              </div>
            </div>
          </div> -->
        <!-- Services item -->
        <!-- <div class="col-md-6 col-lg-4 col-xs-12">
            <div class="services-item wow fadeInRight" data-wow-delay="1.2s">
              <div class="icon">
                <i class="lni-layers"></i>
              </div>
              <div class="services-content">
                <h3><a href="#">UI/UX Design</a></h3>
                <p>Ut maximus enim dolor. Aenean auctor risus eget tincidunt lobortis. Donec tincidunt bibendum gravida. </p>
              </div>
            </div>
          </div> -->
        <!-- Services item -->
        <!-- <div class="col-md-6 col-lg-4 col-xs-12">
            <div class="services-item wow fadeInRight" data-wow-delay="1.5s">
              <div class="icon">
                <i class="lni-mobile"></i>
              </div>
              <div class="services-content">
                <h3><a href="#">App Development</a></h3>
                <p>Ut maximus enim dolor. Aenean auctor risus eget tincidunt lobortis. Donec tincidunt bibendum gravida. </p>
              </div>
            </div>
          </div> -->
        <!-- Services item -->
        <!-- <div class="col-md-6 col-lg-4 col-xs-12">
            <div class="services-item wow fadeInRight" data-wow-delay="1.8s">
              <div class="icon">
                <i class="lni-rocket"></i>
              </div>
              <div class="services-content">
                <h3><a href="#">User Friendly interface</a></h3>
                <p>Ut maximus enim dolor. Aenean auctor risus eget tincidunt lobortis. Donec tincidunt bibendum gravida. </p>
              </div>
            </div>
          </div> -->
      </div>
    </div>
  </section>
  <!-- Services Section End -->

  <!-- About Section start -->
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
  <!-- About Section End -->

  <!-- Features Section Start -->
  <x-features/>
  {{-- <section id="features" class="section-padding">
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
                <p style="color: black;">Instantly verify email addresses as they are entered, ensuring that each
                  address is accurate and deliverable before sending any communication.</p>
              </div>
            </div>
            <div class="box-item wow fadeInLeft" data-wow-delay="0.4s">
              <span class="icon">
                <i class="lni-check-mark-circle"></i>
              </span>
              <div class="text">
                <h4>Detailed Validation Results</h4>
                <p style="color: black;">Receive comprehensive reports that provide insights into the validity, risk
                  level, and overall health of each email address.</p>
              </div>
            </div>
            <div class="box-item wow fadeInLeft" data-wow-delay="0.8s">
              <span class="icon">
                <i class="lni-ticket-alt"></i>
              </span>
              <div class="text">
                <h4>Instant Feedback</h4>
                <p style="color: black;">Get immediate notifications about the status of email addresses, including
                  whether they are valid, invalid, or risky, allowing for quick decision-making.</p>
              </div>
            </div>
            <div class="box-item wow fadeInLeft" data-wow-delay="0.9s">
              <span class="icon">
                <i class="lni-display"></i>
              </span>
              <div class="text">
                <h4>Easy-To-Use Interface</h4>
                <p style="color: black;">Navigate through a user-friendly dashboard designed for efficiency and ease of
                  use, making the email verification process seamless for users of all technical levels.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
          <div class="show-box wow fadeInUp" data-wow-delay="0.2s">
            <img style="margin-top: 10em;" src="assets/feature/intro-mobile.png" alt="">
          </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
          <div class="content-right">
            <div class="box-item wow fadeInRight" data-wow-delay="0.2s">
              <span class="icon">
                <i class="lni-rocket"></i>
              </span>
              <div class="text">
                <h4>High-Speed Bulk Verification</h4>
                <p style="color: black;">Verify large volumes of email addresses quickly and efficiently, reducing the
                  time and effort needed to clean and maintain email lists.</p>
              </div>
            </div>
            <div class="box-item wow fadeInRight" data-wow-delay="0.4s">
              <span class="icon">
                <i class="lni-layers"></i>
              </span>
              <div class="text">
                <h4>99% Accuracy Rate</h4>
                <p style="color: black;">Rely on a highly accurate verification system that ensures your email lists are
                  free from invalid addresses, enhancing deliverability and campaign success rates.</p>
              </div>
            </div>
            <div class="box-item wow fadeInRight" data-wow-delay="0.8s">
              <span class="icon">
                <i class="lni-notepad"></i>
              </span>
              <div class="text">
                <h4>Comprehensive Reporting</h4>
                <p style="color: black;">Access detailed reports that summarize the verification results, providing
                  valuable insights into email list quality and areas for improvement.</p>
              </div>
            </div>
            <div class="box-item wow fadeInRight" data-wow-delay="0.9s">
              <span class="icon">
                <i class="lni-cloud-upload"></i>
              </span>
              <div class="text">
                <h4>Easy Upload & Management</h4>
                <p style="color: black;">Simplify the process of managing email lists with an intuitive upload feature,
                  allowing users to quickly add and organize their lists for bulk verification.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section> --}}
  <!-- Features Section End -->

  <!-- Call To Action Section Start -->
  <!-- <section id="cta" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-xs-12 wow fadeInLeft" data-wow-delay="0.3s">
          <div class="cta-text">
            <h4>You're Using Free Lite Version</h4>
            <h5>Please purchase full version of the template to get all features and facilities</h5>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 text-right wow fadeInRight" data-wow-delay="0.3s">
          </br><a rel="nofollow" href="" class="btn btn-common">Purchase Now</a>
        </div>
      </div>
    </div>
  </section> -->
  <!-- Call To Action Section Start -->

  <!-- Team Section Start -->
  <!-- <section id="team" class="section-padding bg-gray">
    <div class="container">
      <div class="section-header text-center">
        <h2 class="section-title wow fadeInDown animated" data-wow-delay="0.3s" style="visibility: visible;-webkit-animation-delay: 0.3s; -moz-animation-delay: 0.3s; animation-delay: 0.3s;">
          Meet our team</h2>
        <div class="shape wow fadeInDown animated" data-wow-delay="0.3s" style="visibility: visible;-webkit-animation-delay: 0.3s; -moz-animation-delay: 0.3s; animation-delay: 0.3s;">
        </div>
      </div>
      <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 team-member" data-target="#CEO" data-toggle="modal" data-image="assets/team/CEO.png">
                <img src="assets/team/CEO.png" alt="Michael Johnson">
                <h5 style="color:black">Michael Johnson</h5>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 team-member" data-target="#CTO" data-toggle="modal" data-image="assets/team/CTO.png">
                <img src="assets/team/CTO.png" alt="David Thompson">
                <h5 style="color:black">David Thompson</h5>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 team-member" data-target="#CMO" data-toggle="modal" data-image="assets/team/CMO.png">
                <img src="assets/team/CMO.png" alt="Michael Johnson">
                <h5 style="color:black">Sarah Miller</h5>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 team-member" data-target="#CFO" data-toggle="modal" data-image="assets/team/CFO.png">
                <img src="assets/team/CFO.png" alt="David Thompson">
                <h5 style="color:black">Emily Davis</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 team-member" data-target="#LegalHead" data-toggle="modal" data-image="assets/team/Legal Head.png">
                <img src="assets/team/Legal Head.png" alt="Michael Johnson">
                <h5 style="color:black">Jessica Taylor</h5>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 team-member" data-target="#FinanceHead" data-toggle="modal" data-image="assets/team/Finance Head.png">
                <img src="assets/team/Finance Head.png" alt="David Thompson">
                <h5 style="color:black">Olivia Brown</h5>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 team-member" data-target="#ComplianceHead" data-toggle="modal" data-image="assets/team/Compliance Head.png">
                <img src="assets/team/Compliance Head.png" alt="Michael Johnson">
                <h5 style="color:black">James Anderson</h5>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6 team-member" data-target="#ITHead" data-toggle="modal" data-image="assets/team/IT Head.png">
                <img src="assets/team/IT Head.png" alt="David Thompson">
                <h5 style="color:black">Christopher Lee</h5>
            </div>
        </div>
    </div> -->

    
    <!-- Modal for 360 Viewer -->
    <!-- <div class="modal fade" id="CEO" tabindex="-1" role="dialog" aria-labelledby="CEOLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div id="viewer1">
                  <div class="team-item">
                    <div class="team-img">
                      <img class="img-fluid" src="assets/team/CEO.png" alt="">
                    </div>
                    <div class="contetn">
                      <div class="info-text">
                        <h3><a href="#">Michael Johnson</a></h3>
                        <p style="color: black;">CEO</p>
                      </div>
                      <p style="color: black;">As the CEO, Michael Johnson oversees the company's strategic direction and operations, ensuring alignment with the vision and goals. With over 20 years of experience in the tech industry, he drives innovation and growth, fostering a culture of excellence and integrity.</p>
                        
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="CTO" tabindex="-1" role="dialog" aria-labelledby="CTOLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div id="viewer1">
                  <div class="team-item">
                    <div class="team-img">
                      <img class="img-fluid" src="assets/team/CTO.png" alt="">
                    </div>
                    <div class="contetn">
                      <div class="info-text">
                        <h3><a href="#">David Thompson</a></h3>
                        <p style="color: black;">CTO</p>
                      </div>
                      <p style="color: black;">David Thompson, our CTO, leads the technological advancements and development teams. His expertise in software engineering and cybersecurity ensures that our email validation systems are robust, secure, and cutting-edge.</p>
                        
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="CMO" tabindex="-1" role="dialog" aria-labelledby="CMOLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div id="viewer1">
                  <div class="team-item">
                    <div class="team-img">
                      <img class="img-fluid" src="assets/team/CMO.png" alt="">
                    </div>
                    <div class="contetn">
                      <div class="info-text">
                        <h3><a href="#">Sarah Miller</a></h3>
                        <p style="color: black;">CMO</p>
                      </div>
                      <p style="color: black;">Sarah Miller, our CMO, spearheads the marketing strategies and brand positioning. With a knack for digital marketing and customer engagement, she drives brand awareness and growth through innovative campaigns and strategic partnerships.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="CFO" tabindex="-1" role="dialog" aria-labelledby="CFOLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div id="viewer1">
                  <div class="team-item">
                    <div class="team-img">
                      <img class="img-fluid" src="assets/team/CFO.png" alt="">
                    </div>
                    <div class="contetn">
                      <div class="info-text">
                        <h3><a href="#">Emily Davis</a></h3>
                        <p style="color: black;">CFO</p>
                      </div>
                      <p style="color: black;">As the CFO, Emily Davis manages the financial planning, risk management, and reporting. Her extensive background in finance and strategic planning ensures the company's financial health and sustainability.</p> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="LegalHead" tabindex="-1" role="dialog" aria-labelledby="LegalHeadLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div id="viewer1">
                  <div class="team-item">
                    <div class="team-img">
                      <img class="img-fluid" src="assets/team/Legal Head.png" alt="">
                    </div>
                    <div class="contetn">
                      <div class="info-text">
                        <h3><a href="#">Jessica Taylor</a></h3>
                        <p style="color: black;">Legal Head</p>
                      </div>
                      <p style="color: black;">Jessica Taylor, our Legal Head, oversees all legal matters, ensuring compliance with regulations and mitigating risks. Her expertise in corporate law and her meticulous approach safeguard the company's interests and assets.</p>
                        
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="FinanceHead" tabindex="-1" role="dialog" aria-labelledby="FinanceHeadLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div id="viewer1">
                  <div class="team-item">
                    <div class="team-img">
                      <img class="img-fluid" src="assets/team/Finance Head.png" alt="">
                    </div>
                    <div class="contetn">
                      <div class="info-text">
                        <h3><a href="#">Olivia Brown</a></h3>
                        <p style="color: black;">Finance Head</p>
                      </div>
                      <p style="color: black;">Olivia Brown, the Finance Head, is responsible for managing the company's financial operations and budgeting. Her strong analytical skills and attention to detail ensure efficient financial management and reporting.</p>
                        
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ComplianceHead" tabindex="-1" role="dialog" aria-labelledby="ComplianceHeadLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div id="viewer1">
                  <div class="team-item">
                    <div class="team-img">
                      <img class="img-fluid" src="assets/team/Compliance Head.png" alt="">
                    </div>
                    <div class="contetn">
                      <div class="info-text">
                        <h3><a href="#">James Anderson</a></h3>
                        <p style="color: black;">Compliance Head</p>
                      </div>
                      <p style="color: black;">James Anderson, our Compliance Head, ensures that all operations and procedures comply with regulatory requirements. His extensive knowledge in compliance and risk management helps maintain the highest standards of integrity and accountability.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="ITHead" tabindex="-1" role="dialog" aria-labelledby="ITHeadLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <div id="viewer1">
                  <div class="team-item">
                    <div class="team-img">
                      <img class="img-fluid" src="assets/team/IT Head.png" alt="">
                    </div>
                    <div class="contetn">
                      <div class="info-text">
                        <h3><a href="#">Christopher Lee</a></h3>
                        <p style="color: black;">IT Head</p>
                      </div>
                      <p style="color: black;">Christopher Lee, the IT Head, oversees the information technology infrastructure and operations. His expertise in IT management and system architecture ensures that our technology is reliable, efficient, and secure.</p> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>

  </section> -->
  <!-- Team Section End -->

  <!-- Pricing section Start -->
  {{-- <section id="pricing" class="section-padding">
    <div class="container">
      <div class="section-header text-center">
        <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Plans & Pricing</h2>
        <h6 class="wow fadeInDown" data-wow-delay="0.4s" style="color:black">Try first, decide later, No credit card required!</h6>
        <div class="shape wow fadeInDown" data-wow-delay="0.5s"></div>
      </div>
      <div class="row wow fadeInDown"  data-wow-delay="1.2s">
          <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 2em;">
            <h4 class="price-logo"><img src="assets/pricing/Verification Credits.png" alt=""></h4>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 2em;">
            <h4 class="price-logo"><img class="img-fluid" src="assets/pricing/Logo_55.png" alt=""></h4>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 2em;">
            <h4 class="price-logo"><img class="img-fluid" src="assets/pricing/neverbounce-logo-black-new.png" alt=""></h4>
          </div>
          <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 2em;">
            <h4 class="price-logo"><img class="img-fluid" style="margin-bottom: 1em;" src="assets/pricing/ZeroBounce_55.png" alt=""></h4>
          </div>
      </div>
      <div class="row wow fadeInDown"  data-wow-delay="1.4s">
          <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 2em;">
            <h5 style="color: black; ">5,000 Verifications</h5>
            <div class="shape"></div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 2em;">
            <button class="btn btn-common" style="font-weight:bold">$9</button>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 2em;">
            <button class="btn-comparision">$40</button>
          </div>
          <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 2em;">
            <button class="btn-comparision">$45</button>
          </div>
      </div>
      <div class="row wow fadeInDown"  data-wow-delay="1.6s">
          <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
            <h5 style="color: black;">10,000 Verifications</h5>
            <div class="shape"></div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            <button class="btn btn-common">$14</button>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$50</button>
          </div>
          <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$80</button>
          </div>
      </div>
      <div class="row wow fadeInDown" data-wow-delay="1.8s">
          <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
            <h5 style="color: black;">25,000 Verifications</h5>
            <div class="shape"></div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            <button class="btn btn-common">$28</button>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$125</button>
          </div>
          <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$190</button>
          </div>
      </div>
      <div class="row wow fadeInDown" data-wow-delay="2.0s">
          <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
            <h5 style="color: black;">50,000 Verifications</h5>
            <div class="shape"></div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            <button class="btn btn-common">$45</button>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$250</button>
          </div>
          <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$375</button>
          </div>
      </div>
      <div class="row wow fadeInDown"  data-wow-delay="2.2s">
          <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
            <h5 style="color: black;">100K Verifications</h5>
            <div class="shape"></div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            <button class="btn btn-common">$75</button>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$400</button>
          </div>
          <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$425</button>
          </div>
      </div>
      <div class="row wow fadeInDown"  data-wow-delay="2.4s">
          <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
            <h5 style="color: black;">200K Verifications</h5>
            <div class="shape"></div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
            <button class="btn btn-common">$125</button>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6" style="margin-top: 1em;">
            <button class="btn-comparision">$800</button>
          </div>
          <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6" style="margin-top: 1em;">
            <button class="btn-comparision">$850</button>
          </div>
      </div>
      <div class="row wow fadeInDown"  data-wow-delay="2.6s">
          <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
            <h5 style="color: black;">500K Verifications</h5>
            <div class="shape"></div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            <button class="btn btn-common">$250</button>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$1500</button>
          </div>
          <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$1800</button>
          </div>
      </div>
      <div class="row wow fadeInDown"  data-wow-delay="2.6s">
          <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3 col-mb-6" style="margin-top: 1em;">
            <h5 style="color: black;">1M Verifications</h5>
            <div class="shape"></div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 col-mb-12" style="margin-top: 1em;">
            <button class="btn btn-common">$450</button>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$3000</button>
          </div>
          <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12" style="margin-top: 1em;">
            <button class="btn-comparision">$2750</button>
          </div>
      </div>
    </div>
  </section> --}}
  <x-price /> 
  <!-- Pricing Table Section End -->
  <!-- Testimonial Section Start -->
  <x-testimonial />
  {{-- <section id="testimonial" class="testimonial section-padding">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div id="testimonials" class="owl-carousel wow fadeInUp" data-wow-delay="1.2s">
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img1.jpg" alt="">
                </div>
                <div class="info">
                  <h2><a href="#">John Smith</a></h2>
                  <h3><a href="#">Marketing Director</a></h3>
                </div>
                <div class="content">
                  <p class="description">Using the email validation service for single emails has drastically improved our communication efficiency. No more bouncing emails!</p>
                  <div class="star-icon mt-3">
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-half"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img2.jpg" alt="">
                </div>
                <div class="info">
                  <h2><a href="#">Laura Evans</a></h2>
                  <h3><a href="#">Sales Manager</a></h3>
                </div>
                <div class="content">
                  <p class="description">The bulk email validation feature has saved us hours of work. Our campaigns now reach the right audience without any hassle.</p>
                  <div class="star-icon mt-3">
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-half"></i></span>
                    <span><i class="lni-star-half"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img3.jpg" alt="">
                </div>
                <div class="info">
                  <h2><a href="#">Michael Brown</a></h2>
                  <h3><a href="#">Digital Marketing Specialist</a></h3>
                </div>
                <div class="content">
                  <p class="description">I highly recommend this email validation service. It's accurate, fast, and reliable, which has significantly reduced our bounce rates.</p>
                  <div class="star-icon mt-3">
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-half"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img3.jpg" alt="">
                </div>
                <div class="info">
                  <h2><a href="#">Jessica Wilson</a></h2>
                  <h3><a href="#">Customer Relations Manager</a></h3>
                </div>
                <div class="content">
                  <p class="description">We've seen a remarkable improvement in our email deliverability since using this service. The single email validation is incredibly precise.</p>
                  <div class="star-icon mt-3">
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-half"></i></span>
                    <span><i class="lni-star-half"></i></span>
                    <span><i class="lni-star-half"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img3.jpg" alt="">
                </div>
                <div class="info">
                  <h2><a href="#">David Johnson</a></h2>
                  <h3><a href="#">Chief Operations Officer</a></h3>
                </div>
                <div class="content">
                  <p class="description">Our email campaigns have never been more effective. The bulk validation ensures that our lists are always up-to-date and clean.</p>
                  <div class="star-icon mt-3">
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-half"></i></span>
                    <span><i class="lni-star-half"></i></span>
                    <span><i class="lni-star-half"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img3.jpg" alt="">
                </div>
                <div class="info">
                  <h2><a href="#">Emily Martinez</a></h2>
                  <h3><a href="#">Communications Director</a></h3>
                </div>
                <div class="content">
                  <p class="description">This email validation service is a game-changer. It has improved our engagement rates and overall email campaign success.</p>
                  <div class="star-icon mt-3">
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-half"></i></span>
                    <span><i class="lni-star-half"></i></span>
                    <span><i class="lni-star-half"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img3.jpg" alt="">
                </div>
                <div class="info">
                  <h2><a href="#">James Taylor</a></h2>
                  <h3><a href="#">E-commerce Manager</a></h3>
                </div>
                <div class="content">
                  <p class="description">The accuracy of the single email validation is outstanding. It has helped us maintain a high level of customer satisfaction.</p>
                  <div class="star-icon mt-3">
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-filled"></i></span>
                    <span><i class="lni-star-half"></i></span>
                    <span><i class="lni-star-half"></i></span>
                    <span><i class="lni-star-half"></i></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img3.jpg" alt="">
                  <div class="info">
                    <h2><a href="#">Sarah Davis</a></h2>
                    <h3><a href="#">Product Manager</a></h3>
                  </div>
                  <div class="content">
                    <p class="description">We rely on the bulk email validation for our large email lists. It's efficient and ensures our messages reach the intended recipients.</p>
                    <div class="star-icon mt-3">
                      <span><i class="lni-star-filled"></i></span>
                      <span><i class="lni-star-filled"></i></span>
                      <span><i class="lni-star-half"></i></span>
                      <span><i class="lni-star-half"></i></span>
                      <span><i class="lni-star-half"></i></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img3.jpg" alt="">
                  <div class="info">
                    <h2><a href="#">Chris Anderson</a></h2>
                    <h3><a href="#">IT Manager</a></h3>
                  </div>
                  <div class="content">
                    <p class="description">The email validation service has significantly reduced our bounce rates and improved our email deliverability. It's a must-have tool.</p>
                    <div class="star-icon mt-3">
                      <span><i class="lni-star-filled"></i></span>
                      <span><i class="lni-star-filled"></i></span>
                      <span><i class="lni-star-half"></i></span>
                      <span><i class="lni-star-half"></i></span>
                      <span><i class="lni-star-half"></i></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img3.jpg" alt="">
                  <div class="info">
                    <h2><a href="#">Olivia Thomas</a></h2>
                    <h3><a href="#">HR Manager</a></h3>
                  </div>
                  <div class="content">
                    <p class="description">The accuracy and speed of the email validation service are impressive. It has helped us maintain a clean and efficient email list.</p>
                    <div class="star-icon mt-3">
                      <span><i class="lni-star-filled"></i></span>
                      <span><i class="lni-star-filled"></i></span>
                      <span><i class="lni-star-half"></i></span>
                      <span><i class="lni-star-half"></i></span>
                      <span><i class="lni-star-half"></i></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img3.jpg" alt="">
                  <div class="info">
                    <h2><a href="#">Daniel Garcia</a></h2>
                    <h3><a href="#">Business Development Director</a></h3>
                  </div>
                  <div class="content">
                    <p class="description">Bulk email validation has streamlined our email marketing efforts. It's a reliable service that we highly recommend.</p>
                    <div class="star-icon mt-3">
                      <span><i class="lni-star-filled"></i></span>
                      <span><i class="lni-star-filled"></i></span>
                      <span><i class="lni-star-half"></i></span>
                      <span><i class="lni-star-half"></i></span>
                      <span><i class="lni-star-half"></i></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="item">
              <div class="testimonial-item">
                <div class="img-thumb">
                  <img src="assets/testimonial/img4.jpg" alt="">
                </div>
                  <div class="info">
                    <h2><a href="#">Megan Harris</a></h2>
                    <h3><a href="#">CEO</a></h3>
                  </div>
                  <div class="content">
                    <p class="description">This email validation service has been invaluable for our business. The single and bulk validations are both incredibly effective and user-friendly.</p>
                    <div class="star-icon mt-3">
                      <span><i class="lni-star-filled"></i></span>
                      <span><i class="lni-star-filled"></i></span>
                      <span><i class="lni-star-half"></i></span>
                      <span><i class="lni-star-half"></i></span>
                      <span><i class="lni-star-half"></i></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>
  </section> --}}

  <!-- Testimonial Section End -->

  <!-- Call To Action Section Start -->
  <!-- <section id="cta" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-xs-12 wow fadeInLeft" data-wow-delay="0.3s">
          <div class="cta-text">
            <h4>Get 30 days free trial</h4>
            <p>Praesent imperdiet, tellus et euismod euismod, risus lorem euismod erat, at finibus neque odio quis
              metus. Donec vulputate arcu quam. </p>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 text-right wow fadeInRight" data-wow-delay="0.3s">
          </br><a href="#" class="btn btn-common">Register Now</a>
        </div>
      </div>
    </div>
  </section> -->
  <!-- Call To Action Section Start -->

  <!-- Contact Section Start -->
  <section id="contact" class="section-padding bg-gray">
    <div class="container">
      <div class="section-header text-center">
        <h2 class="section-title wow fadeInDown" data-wow-delay="0.3s">Contact Us</h2>
        <div class="shape wow fadeInDown" data-wow-delay="0.3s"></div>
      </div>
      <div class="row contact-form-area wow fadeInUp" data-wow-delay="0.3s">
        <div class="col-lg-7 col-md-12 col-sm-12">
          <!-- Call To Action Section Start -->
          <section id="cta" class="section-padding">
            <div class="container">
              <div class="row">
                <div class="col-lg-10 col-md-10 col-xs-12 wow fadeInLeft" data-wow-delay="0.3s">
                  <div class="cta-text">
                    <h4 style="color: black; text-align:center">Simple and Accurate Email Validation Tool</h4>
                    <h5 style="color: black; text-align:center">Over 1 Million users trust bouncee for their real-time email validation and email cleaning services.</h5>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!-- Call To Action Section Start -->
        </div>
        <div class="col-lg-5 col-md-12 col-xs-12">
          <div class="map">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3064.825167622051!2d-89.62002712330093!3d39.81091332941296!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88753a0f4d480bb5%3A0xe27016033bedca9b!2sElm%20St%2C%20Springfield%2C%20IL%2062702%2C%20USA!5e0!3m2!1sen!2sin!4v1720037841562!5m2!1sen!2sin" width="500" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>
    </div>
  </section>

  @endsection