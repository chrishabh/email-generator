@extends('layout.main')


@php
    $headerData = [];
    $headerData['whichPageRequest'] = 'privacy';
@endphp

@section('main-section')
    @push('title')
        <title>Refund Policy | bouncee</title>
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
                <h1 class=" display-5">Refund Policy</h1>
                <p class=""><i>Last updated: April 1, 2025</i></p>
            </div>

            <div class="glass-card">
                <p class="custom-border-bottom"></p>

                <h4 class="section-ka-title">Refunds and Disputes</h4>
                <p>At <strong>bouncee.net</strong>, we offer a free account with <strong> 100 complimentary credits </strong>, allowing new users to explore and evaluate our email verification services at no cost. This risk-free trial provides a clear opportunity to experience the platform before purchasing additional verification credits.<br>
                    We encourage all users to make full use of these free credits to ensure the service meets their needs prior to making a purchase.</p>
                    <ul>
                        <li><strong>Non-refundable Payments</strong>All payments made on bouncee.net are <strong>non-refundable.</strong> By using our services, you agree to this policy.</li>
                        <li><strong>No Refund for Unknown or Catch-All Results:</strong>Email verification outcomes labeled as <i>Unknown</i> or <i> Catch-all</i> are considered valid results, and therefore do not qualify for a refund or credit reimbursement.</li>
                         
                    </ul> 
                <p class="custom-border-bottom"></p>

                <h4 class="section-ka-title">Contact Us</h4>
                <p>If you have questions or concerns about this Privacy Policy, feel free to <a href="mailto:support@bouncce.net">contact us.</a></p>

            </div>
        </div>

    </section>
@endsection
