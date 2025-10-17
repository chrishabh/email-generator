@extends('layout.main')

@php
    $headerData = ['whichPageRequest' => 'whyus'];
@endphp

@section('main-section')
@push('title')
    <title>Why Choose Bouncee | bouncee</title>
@endpush

<style>
    .why-us {
        background: linear-gradient(180deg, #f9f5ff 0%, #ffffff 100%);
        padding: 80px 20px;
        text-align: center;
        transition: background 0.5s ease, color 0.5s ease;
        margin-top: 6rem;
    }
    @media (prefers-color-scheme: dark) {
        .why-us {
            background: linear-gradient(180deg, #0f0f11 0%, #1a1a1d 100%);
        }
    }

    .why-us h2 {
        font-weight: 700;
        color: #5e2ced;
        margin-bottom: 15px;
        font-size: 2.3rem;
        transition: color 0.4s ease;
    }
    @media (prefers-color-scheme: dark) {
        .why-us h2 { color: #bda8ff; }
    }

    .why-us p.subtitle {
        color: #4a4a4a;
        max-width: 650px;
        margin: 0 auto 50px;
        font-size: 1.1rem;
        transition: color 0.4s ease;
    }
    @media (prefers-color-scheme: dark) {
        .why-us p.subtitle { color: #cfcfcf; }
    }

    .why-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 25px;
        justify-content: center;
        align-items: stretch;
      }

    .why-card {
        background: #f5ecff;
        border-radius: 16px;
        padding: 35px 25px;
        transition: all 0.4s ease;
        text-align: left;
        box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    .why-card:hover {
        transform: translateY(-6px);
        background: #ede3ff;
        box-shadow: 0 10px 20px rgba(93,44,237,0.15);
    }

    @media (prefers-color-scheme: dark) {
        .why-card {
            background: #23232a;
            box-shadow: 0 3px 8px rgba(255,255,255,0.03);
        }
        .why-card:hover {
            background: #2e2e36;
            box-shadow: 0 10px 20px rgba(93,44,237,0.3);
        }
    }

    .why-card-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        background: #5e2ced;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 26px;
        margin-bottom: 20px;
        box-shadow: 0 4px 10px rgba(93,44,237,0.4);
    }

    .why-card h4 {
        color: #3d2b7a;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 1.15rem;
        transition: color 0.4s ease;
    }
    .why-card p {
        color: #4a4a4a;
        font-size: 0.95rem;
        line-height: 1.6;
        transition: color 0.4s ease;
    }

    @media (prefers-color-scheme: dark) {
        .why-card h4 { color: #d6cfff; }
        .why-card p { color: #c9c9c9; }
    }

    .cta-section {
        margin-top: 70px;
        text-align: center;
    }
    .cta-section a {
        display: inline-block;
        background: #5e2ced;
        color: #fff;
        padding: 14px 35px;
        border-radius: 30px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.3s ease, transform 0.3s ease;
        box-shadow: 0 5px 15px rgba(93,44,237,0.4);
    }
    .cta-section a:hover {
        background: #4b22c8;
        transform: translateY(-3px);
    }

    [data-animate] {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.7s ease-out;
    }
    [data-animate].active {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="why-us">
    <h1>Why Choose Bouncee?</h1>
    <p class="subtitle">
        Experience enterprise-grade accuracy, security, and performance — all in one simple and powerful email verification platform.
    </p>

    <div class="why-cards">
        <div class="why-card" data-animate>
            <div class="why-card-icon">
                <i class="bi bi-lightning-charge"></i>
            </div>
            <h4>99% Accuracy</h4>
            <p>Multi-layer verification engine combining syntax, MX, SMTP, and AI scoring for unmatched precision.</p>
        </div>

        <div class="why-card" data-animate>
            <div class="why-card-icon">
                <i class="bi bi-shield-check"></i>
            </div>
            <h4>Secure & Compliant</h4>
            <p>GDPR-compliant, end-to-end encrypted, and data auto-deleted after processing — privacy first.</p>
        </div>

        <div class="why-card" data-animate>
            <div class="why-card-icon">
                <i class="bi bi-graph-up"></i>
            </div>
            <h4>Boost Deliverability</h4>
            <p>Eliminate invalid and disposable emails instantly, protect sender reputation, and increase inbox rates.</p>
        </div>

        <div class="why-card" data-animate>
            <div class="why-card-icon">
                <i class="bi bi-cloud-upload"></i>
            </div>
            <h4>Bulk & API Ready</h4>
            <p>Verify large email lists or connect your platform in real-time via Bouncee’s developer API.</p>
        </div>

        <div class="why-card" data-animate>
            <div class="why-card-icon">
                <i class="bi bi-speedometer2"></i>
            </div>
            <h4>Lightning Fast</h4>
            <p>Process thousands of verifications per minute on a globally distributed, optimized network.</p>
        </div>

        <div class="why-card" data-animate>
            <div class="why-card-icon">
                <i class="bi bi-star"></i>
            </div>
            <h4>Trusted by Teams</h4>
            <p>From SaaS builders to marketing pros — Bouncee delivers reliability, speed, and precision they depend on.</p>
        </div>
    </div>

    <div class="cta-section" data-animate>
        <a href="/signup">Start Verifying for Free</a>
    </div>
</div>

<script>
    // Animate on scroll
    document.addEventListener('scroll', () => {
        document.querySelectorAll('[data-animate]').forEach(el => {
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight - 100) {
                el.classList.add('active');
            }
        });
    });
</script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


@endsection
