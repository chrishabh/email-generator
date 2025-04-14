@extends('layout.main')


@php
    $headerData = [];
    $headerData['whichPageRequest'] = 'privacy';
@endphp

@section('main-section')
    @push('title')
        <title>Cookie Policy | bouncee</title>
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
        /* border-bottom: 2px solid rgb(10, 93, 170);; */
        padding-bottom: 5px;
    }

    .custom-border-bottom{
        border-bottom: 2px solid #dee2e6 ;
        padding-top: 2em;
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
              <h1 class=" display-5">Cookie Policy</h1>
              <p class=""><i>Last updated:  April 1, 2025</i></p>
            </div>
        
            <div class="glass-card">
              <p>This Cookie Policy explains how <strong> bouncee.net</strong> uses cookies and similar technologies to enhance your browsing experience, analyze traffic, and support essential site functions.</p>
 
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">1. What Are Cookies?</h4> 
              <p>Cookies are small text files placed on your device when you visit a website. They help us remember your preferences, enhance functionality, and improve site performance. Cookies are a standard part of modern web applications.<br>
                For more general information on cookies, you can visit the <a href="https://en.wikipedia.org/wiki/HTTP_cookie">Wikipedia article on HTTP Cookies.</a></p>
              <p class="custom-border-bottom"></p>  

              <h4 class="section-ka-title">2. How We Use Cookies</h4>
              <p>We use cookies for a variety of reasons, including:</p>
              <ul>
                <li>Improving user experience</li>
                <li>Remembering login details</li>
                <li>Understanding how our site is used</li>
                <li>Testing new features</li>
                <li>Tracking conversions and performance</li>
              </ul>
              <p>Disabling cookies may affect the functionality of this site and many other websites. We recommend keeping cookies enabled for the best experience.</p>
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">3. Disabling Cookie</h4>
              <p>You can modify your browser settings to disable or block cookies. Please note that doing so may limit certain features or functions of the website.<br>
                For instructions on managing cookies, check your browser's Help section.</p>
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">4. Cookies We Set</h4>

              <ul>
                <li><strong>Account Cookies:</strong> These support account creation, management, and preferences. Some may remain after logout to remember your settings.</li>
                <li><strong>Login Cookies:</strong> Used to keep you logged in as you navigate the site, reducing the need to log in repeatedly.</li>
              </ul> 
               <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">5. Third-Party Cookies</h4>
              <p>We may use trusted third-party services that also set cookies:</p>
              <ul>
                <li><strong> Google Analytics:</strong> Helps us understand how visitors use the site, such as time spent on pages and navigation paths. This data helps us optimize content and design.<br>More info: Google Analytics Cookie Usage</li>
                <li><strong> A/B Testing Cookies:</strong> When we test new features, cookies may ensure a consistent experience and help us understand which changes users prefer.</li>
                <li><strong> Conversion Tracking:</strong> Helps us analyze how users engage with our services, such as purchases or sign-ups, enabling us to improve performance and pricing strategies.</li>
              </ul> 

              <p class="custom-border-bottom"></p>  
  
              <h4 class="section-ka-title">11. Contact Us</h4> 
              <p>If you have questions or concerns about this Privacy Policy, feel free to contact us at:: <strong>support@bouncce.net</strong></p>
    
            </div>
        </div> 
        
    </section>
@endsection
