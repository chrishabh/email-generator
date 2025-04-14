@extends('layout.main')


@php
    $headerData = [];
    $headerData['whichPageRequest'] = 'privacy';
@endphp

@section('main-section')
    @push('title')
        <title>Privacy Policy | bouncee</title>
    @endpush
    @push('styles')
        <link rel="stylesheet" href="singleEmailVerification-assets/css/index.css">
        <style> 
    .glass-card {
      background: rgba(255, 255, 255, 0.1);
      /* border-radius: 20px; */
      padding: 0 2rem;
      /* backdrop-filter: blur(10px); */
      -webkit-backdrop-filter: blur(10px);
      /* box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); */
      /* border: 1px solid rgba(255, 255, 255, 0.2); */
    }

    h1, h4 {
      font-weight: bold;
    }

    .section-ka-title {
        font-size: 2em; 
        color: rgb(10, 93, 170);
        margin-top: 2rem;
        margin-bottom: 1em;
        
        padding-bottom: 5px;
    }

    .custom-border-bottom{
        border-bottom: 2px solid #dee2e6;
        padding-top: 2em;ant
    }
    ul {
      padding-left: 1.2rem;
    }
 
     
    .glass-card p, .glass-card ul{
        font-size: 1rem;
    }

    .glass-card ul li{
        list-style: disc;
    }

    .glass-card ul li::marker {
        color: rgb(10, 93, 170);
    }

    .para-ans {
        padding: 0 0 1em 1em;
    }

    .point-head{
        font-weight: bold;
        font-size: 1rem;
    }

    @media (max-width: 576px) {
      .glass-card {
        padding: 1.25rem;
      }
    }
        </style>
    @endpush
    <section class="hero-section ani_has_move_parallax header-ver-1 pb-0">

        <div class="container py-5">
            <div class="text-center mb-5">
              <h1 class=" display-5">Privacy Policy</h1>
              <p class="">Last updated:  April 1, 2025</p>
            </div>
        
            <div class="glass-card">
              <p class="">Your privacy is very important to us.This Privacy Policy outlines how we collect, use, and safeguard your information at <strong>bouncee.net</strong>. It applies only to data collected through this website.</p>
              <div class="custom-border-bottom"></div>
              <h4 class="section-ka-title">1. Information We Collect</h4>
              <h5 class="point-head">a. Non-Personally Identifiable Information</h5>
              <p class="para-ans">Like most websites, we collect non-personally identifying information such as browser type, language preference, referring site, and the date and time of each visitor request. This information helps us understand how users interact with our website and improve their experience.</p>
        
              <h5 class="point-head">b. Personally Identifiable Information</h5>
              <p class="para-ans">Some visitors may choose to interact with bouncee.net in ways that require us to collect personally identifiable information. For example, if you register on our site, we may request your name and email address.</p><br>
              We also collect IP addresses for logged-in users for purposes of security and usage monitoring.
              <div class="custom-border-bottom"></div>
              <h4 class="section-ka-title ">2. Use of Information</h4>
              <ul>
                <li>Operate and improve our website and service</li>
                <li>Communicate with users</li>
                <li>Monitor and analyze usage trend</li>
                <li>Comply with legal obligation</li>
              </ul>
              <p class="">We <strong>never sell or rent</strong> your personal data.</p>
              <div class="custom-border-bottom"></div>
              <h4 class="section-ka-title ">3. Data Security</h4>
              <p>We take the security of your data seriously. Our systems use industry-standard measures, including:</p>
              <ul class="pb-2">
                <li>Encryption of data in transit and at rest</li>
                <li>Access controls and user authentication</li>
                <li>Ongoing security audits and monitoring</li>
              </ul>
              <p class="">We also comply with relevant data protection laws, including the General Data Protection Regulation (GDPR), where applicable.</p>
              <div class="custom-border-bottom"></div>
              <h4 class="section-ka-title">4. Cookies</h4>
              <p>bouncee.net uses cookies to enhance user experience, personalize content, and store preferences. Cookies are small data files stored on your device. You may choose to disable cookies via your browser settings, but this may limit functionality on our website.</p><br>
              <br>
              <p>By continuing to use bouncee.net without changing your cookie settings, you agree to our use of cookies.</p>
        
              <h4 class="section-ka-title">5. Changes to This Policy</h4>
              <p>We may update this Privacy Policy from time to time. Any changes will be posted on this page with an updated date. Continued use of the website after changes means you accept the updated terms.</p>
              <div class="custom-border-bottom"></div>
              <h4 class="section-ka-title">6. Contact Us</h4>
              <p>If you have questions or concerns about this Privacy Policy, feel free to <a href="mailto:support@bouncce.net">contact us.</a></p>
    
            </div>
        </div> 
        
    </section>
@endsection
