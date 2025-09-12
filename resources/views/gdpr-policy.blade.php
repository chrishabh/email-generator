@extends('layout.main')


@php
    $headerData = [];
    $headerData['whichPageRequest'] = 'privacy';
@endphp

@section('main-section')
    @push('title')
    <title>GDPR Privacy Policy – Bouncee’s Data Protection Standards</title>
    <meta name="description" content="Read how Bouncee complies with GDPR. Learn about data rights, processing principles, and how we protect personal data with transparency and security.">
    <meta name="keywords" content="GDPR privacy policy, bouncee data protection, personal data security, data rights, GDPR compliance, email verification privacy">
    <meta name="author" content="bouncee">

    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="GDPR Privacy Policy – Bouncee’s Data Protection Standards">
    <meta property="og:description" content="Read how Bouncee complies with GDPR. Learn about data rights, processing principles, and how we protect personal data with transparency and security.">
    <meta property="og:image" content="https://bouncee.net/assets/img/logo.png">
    <meta property="og:type" content="website">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="GDPR Privacy Policy – Bouncee’s Data Protection Standards">
    <meta name="twitter:description" content="Read how Bouncee complies with GDPR. Learn about data rights, processing principles, and how we protect personal data with transparency and security.">
    <meta name="twitter:image" content="https://bouncee.net/assets/img/logo.png">
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

            h1,
            h4 {
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

            .custom-border-bottom {
                border-bottom: 2px solid #dee2e6;
                padding-top: 2em;
            }

            ul {
                padding-left: 1.2rem;
            }


            .glass-card p,
            .glass-card ul {
                font-size: 1rem;
            }

            .glass-card ul li {
                list-style: disc;
            }

            .glass-card ul li::marker {
                color: rgb(10, 93, 170);
            }

            .para-ans {
                padding: 0 0 1em 1em;
            }

            .point-head {
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
                <h1 class=" display-5">GDPR Privacy Policy</h1>
                <p class=""><i>Last updated: April 1, 2025</i></p>
            </div>

            <div class="glass-card">
                <p class="custom-border-bottom"></p>

                <h4 class="section-ka-title"> Definitions</h4>
                <h5 class="point-head">GDPR</h5>
                <p class="para-ans">The General Data Protection Regulation (EU) 2016/679 — a regulation in EU law on data
                    protection and privacy for individuals within the European Economic Area (EEA).</p>
                <h5 class="point-head">Data Controller</h5>
                <p class="para-ans">The individual or entity that determines the purposes and means of processing personal
                    data</p>
                <h5 class="point-head">Data Processor</h5>
                <p class="para-ans">A party that processes personal data on behalf of the Data Controller.</p>
                <h5 class="point-head">Data Subject</h5>
                <p class="para-ans">An individual whose personal data is being collected, held, or processed.</p>

                <p class="custom-border-bottom"></p>

                <h4 class="section-ka-title">Principles for Processing Personal Data</h4>
                <ul>
                    <li><strong>Lawfulness, Fairness, and Transparency:</strong>Personal data must be processed lawfully,
                        fairly, and in a transparent manner in relation to the data subject.</li>
                    <li><strong>Purpose Limitation:</strong>Data should be collected for specified, explicit, and legitimate
                        purposes only.</li>
                    <li><strong>Data Minimization:</strong>Only data necessary for the intended purposes should be collected
                        and processed.</li>
                    <li><strong>Accuracy:</strong>Reasonable steps must be taken to ensure personal data is accurate and
                        up-to-date.</li>
                    <li><strong>Storage Limitation:</strong>Data should be retained only as long as necessary for the
                        purposes collected.</li>
                    <li><strong>Integrity and Confidentiality:</strong>Personal data must be processed securely using
                        appropriate technical and organizational measures.</li>
                </ul>
                <p class="custom-border-bottom"></p>

                <h4 class="section-ka-title">What Personal Data We Collect</h4>
                <p>We may collect the following types of personal data:</p>
                <ul>
                    <li>Email address</li>
                    <li>First and last name</li>
                    <li>Phone number</li>
                    <li>Billing and contact address (including city, state/province, postal code)</li>
                </ul>

                <p class="custom-border-bottom"></p>

                <h4 class="section-ka-title">How We Use Your Personal Data</h4>
                <p>We use your information for the following purposes:</p>
                <ul>
                    <li>To communicate important updates regarding services or terms</li>
                    <li>To offer customer support </li>
                    <li>To improve service functionality through analytics </li>
                    <li>To detect and prevent technical or security issues</li>
                </ul>
                <p class="custom-border-bottom"></p>

                <h4 class="section-ka-title">Legal Basis for Processing</h4>
                <p>We process personal data under the following lawful bases:</p>
                <ul>
                    <li>Performance of a contract </li>
                    <li>Your explicit consent </li>
                    <li>Legitimate interests not overridden by your rights </li>
                    <li>Compliance with legal obligations</li>
                </ul>
                <p class="custom-border-bottom"></p>

                <h4 class="section-ka-title">Data Retention</h4>
                <p>We retain your personal data only for as long as necessary to fulfill the purposes for which it was collected, or as required by law. When data is no longer needed, we will securely delete or anonymize it.</p>
                <p class="custom-border-bottom"></p>

                <h4 class="section-ka-title">Your Data Protection Rights (EEA Residents)</h4>
                <p>If you are located in the EEA, you have the following rights:</p>
                <ul> 
                    <li><strong>Access – </strong> access to your personal data. </li>
                    <li><strong>Rectification – </strong> correction of inaccurate or incomplete data.</li>
                    <li><strong>Erasure – </strong> deletion of your personal data.</li>
                    <li><strong>Objection – </strong> to our processing of your data under certain circumstances.</li>
                    <li><strong>Restriction – </strong> restriction of processing under specific conditions.</li>
                    <li><strong>Portability – </strong>Receive your personal data in a structured, machine-readable format.</li>
                    <li><strong>Withdraw Consent –</strong> Withdraw previously given consent at any time.</li>
                </ul>
                <p>To exercise your rights, please contact us</p>
                <p class="custom-border-bottom"></p>

                <h4 class="section-ka-title">Contact Us</h4>
                <p>If you have questions or concerns about this Privacy Policy, feel free to <a href="mailto:support@bouncce.net">contact us.</a></p>

            </div>
        </div>

    </section>
@endsection
