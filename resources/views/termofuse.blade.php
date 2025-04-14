@extends('layout.main')


@php
    $headerData = [];
    $headerData['whichPageRequest'] = 'privacy';
@endphp

@section('main-section')
    @push('title')
        <title>Term of Use | bouncee</title>
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
              <h1 class=" display-5">Terms of Use</h1>
              <p class=""><i>Last updated:  April 1, 2025</i></p>
            </div>
        
            <div class="glass-card">
              <p>Welcome to <strong> bouncee.net.</strong> These Terms of Use (“Terms”) outline the rules and conditions that govern your access to and use of our website and services.</p>
              <p>By accessing or using bouncee.net (the “Website”) and related services (collectively, the “Services”), you agree to be bound by these Terms. If you do not agree, please do not use the Website or Services.</p>
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">1.Definitions</h4> 
              <ul>
                <li><strong>Terms and Conditions:</strong> Includes this Terms of Use, Privacy Policy, Cookie Policy, and any additional legal notices available on bouncee.net.</li>
                <li><strong> Website:</strong> Refers to bouncee.net and any of its subdomains.</li>
                <li> <strong>Services:</strong> Refers to all features and offerings made available via the Website, including email verification tools, APIs, and related content.</li>
                <li><strong>User: </strong> Any individual or organization who accesses or creates an account on the Website.</li>
                <li><strong>User: </strong>: Application programming interface used to connect bouncee.net's features with external systems.</li>
              </ul>
              <p class="custom-border-bottom"></p>  

              <h4 class="section-ka-title">2. Account Responsibilities</h4>
              <p>When creating an account, you must provide accurate, complete, and current information. You are responsible for maintaining the confidentiality of your account credentials and API key. You agree to notify us immediately of any unauthorized use of your account. We reserve the right to suspend or terminate accounts that provide false information or violate our policies.</p>
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">3. Use of Services</h4>
              <p>You agree to use the Services only for lawful purposes and in accordance with applicable laws and regulations. You may not use automated tools (bots, scripts, etc.) unless explicitly authorized. Misuse of the Services may result in termination of access.</p>
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">4. Payments and Credits</h4>
              <p>All payments for email verification services are non-refundable. Credits must be purchased in advance and can be used at any time—they do not expire. One email verification equals one
                credit. We partner with third-party payment providers and may share necessary billing information as outlined in our Privacy Policy.</p> 
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">5. Refund Policy</h4>
              <p>bouncee.net provides 100 free verification credits to allow new users to evaluate the platform. All purchases are final and non-refundable, including outcomes such as “Unknown” or “Catch-all,” which are a known limitation in email verification.</p>
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">6. Communication</h4>
              <p>By signing up, you agree to receive communication from us regarding your account, updates, promotions, and system messages. You may opt out of promotional emails via the unsubscribe link in any email.</p>
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">7. Data Protection and Security</h4>
              <p>We use industry-standard encryption and security measures to safeguard your data. Please refer to our <a href="/privacy" target="_blank">Privacy Policy</a> for full details on how your information is collected and used.</p>
               <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">8. Termination</h4>
              <p>We may suspend or terminate your access at our sole discretion, especially in cases of abuse, fraud, or non-compliance. Suspended or deleted accounts forfeit any remaining credits, and no refunds will be provided.
                <br>
                Creating multiple accounts after termination is prohibited and may result in permanent banning from the platform.</p>
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">9. Limitations and Disclaimers</h4>
              <p>The Services are provided "as is" and "as available." We do not guarantee accuracy, uptime, or that the Services will meet your expectations. bouncee.net shall not be held liable for:</p>
              <ul>
                <li>Indirect or consequential damages</li>
                <li>Service interruptions or data loss</li>
                <li> Refunds for undeliverable or unverified email results</li>
                <li><strong>User: </strong> Any individual or organization who accesses or creates an account on the Website.</li>
                <li>Damages exceeding USD $100</li>
              </ul>
              <p>Use of the Services is entirely at your own risk.</p>
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">10. Changes to Terms</h4>
              <p>We may revise these Terms at any time. Updates will be posted on this page and take effect immediately. Continued use of the Website constitutes acceptance of any modified terms.</p>
              <p class="custom-border-bottom"></p>

              <h4 class="section-ka-title">11. Contact Us</h4> 
              <p>If you have questions or concerns about this Privacy Policy, feel free to <a href="mailto:support@bouncce.net">contact us.</a></p>
            </div>
        </div> 
        
    </section>
@endsection
