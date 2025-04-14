@extends('layout.main')


@php
    $headerData = [];
    $headerData['whichPageRequest'] = 'privacy';
@endphp

@section('main-section')
    @push('title')
        <title>Data Policy | bouncee</title>
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
              <h1 class=" display-5">Data Policy</h1>
              <p class="">Last updated:  April 1, 2025</p>
            </div>
        
            <div class="glass-card">
              <p class="">At  <strong> bouncee.net</strong>, we are committed to safeguarding your privacy and maintaining the confidentiality, integrity, and security of your data. This Data Policy explains how we handle your information, including how long we retain it and the measures we take to protect it.</p>
              <div class="custom-border-bottom"></div>
              <h4 class="section-ka-title">1. Purpose</h4>   
              <p>This policy outlines our data handling practices and retention timelines to ensure transparency and responsible data management. Our goal is to help you understand how long your data is stored, why it is stored, and how it is securely handled.</p>
              <div class="custom-border-bottom"></div>

              <h4 class="section-ka-title ">2.Data Retention</h4>
              <p>We retain user data only for as long as necessary to provide our services, fulfill legal obligations, and support business operations. General retention guidelines are as follows:</p>
              <ul> 
                    <li><strong> Account Information: </strong> Retained as long as your account remains active. Once an account is closed, data is deleted within a reasonable period unless legally required to retain it longer.</li>
                    <li><strong>Email Lists & Verification Results:</strong> Retained for 30 days after upload. After this period, data is securely deleted. Users can also delete lists manually at any time, providing full control over stored data.</li>
              </ul> 
              <div class="custom-border-bottom"></div>

              <h4 class="section-ka-title ">3. How We Use Your Data</h4>
                <ul>  
                    <li><strong>Limited Purpose:</strong> Your data is only used for the purposes clearly stated on our platform (e.g., email verification). We will never use your data for unrelated purposes without your explicit consent.</li>
                    <li><strong>Marketing Communications:</strong> With your permission, we may use your contact information to send occasional updates or promotional messages. You can opt out of marketing emails at any time using the unsubscribe link provided.</li>
              </ul>                
              <div class="custom-border-bottom"></div>

              <h4 class="section-ka-title">4. Data Security & Storage</h4>
              <p>We employ a robust set of technical and organizational safeguards to protect your data:</p> 
               <ul>   
                    <li><strong>Cloud Hosting:</strong> Data is hosted across secure, reliable multi-vendor cloud infrastructure partners, all of whom adhere to strict data protection standards.</li>
                    <li><strong>Encryption:</strong> Data is encrypted both in transit and at rest using current industry-standard protocols.</li>
                    <li><strong>Access Control:</strong> Only authorized personnel can access sensitive data. We enforce role-based access controls (RBAC) and use two-factor authentication (2FA) to prevent unauthorized access.</li>
                    <li><strong>Compliance:</strong> Our data practices fully comply with the General Data Protection Regulation (GDPR) and other applicable privacy laws, ensuring we follow principles like data minimization, purpose limitation, and secure processing.</li>
                </ul>  
                <div class="custom-border-bottom"></div>

              <h4 class="section-ka-title">5. Data Deletion</h4>
              <p>When your data is no longer required or when you choose to delete it, we ensure it is removed or anonymized securely and permanently. You also have the right to:</p>
                <ul>
                    <li>Access your personal data </li>
                    <li>Request corrections </li>
                    <li>Request deletion</li>
                </ul>
                <p>We offer tools and support to make these requests simple and transparent.</p>
              <div class="custom-border-bottom"></div>

              <h4 class="section-ka-title">6. Policy Updates</h4>
              <p>We may revise this Data Policy periodically to reflect changes in regulations, technology, or our internal practices. Any material changes will be posted on this page and, where appropriate, communicated via email or website notification.</p>
              <div class="custom-border-bottom"></div>

              <h4 class="section-ka-title">7. Contact Us</h4>
              <p>If you have questions or concerns about this Privacy Policy, feel free to contact us at:: <strong>support@bouncce.net</strong></p>
    
            </div>
        </div> 
        
    </section>
@endsection
