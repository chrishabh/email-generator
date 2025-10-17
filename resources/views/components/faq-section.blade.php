<style>
    .faq-section {
        max-width: 1100px;
        margin: 0 auto;
        margin-top: 4rem;
        text-align: center;
    }
    .faq-section h2 {
        font-weight: 700;
        color: #5e2ced;
        margin-bottom: 40px;
    }
    .faq-item {
        background: #f5ecff;
        border-radius: 10px;
        margin-bottom: 15px;
        text-align: left;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .faq-item:hover {
        background: #ede3ff;
    }
    .faq-question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        font-weight: 600;
        cursor: pointer;
        color: #3d2b7a;
        font-size: 1.05rem;
    }
    .faq-question span {
        transition: transform 0.3s ease;
        font-size: 24px;
    }
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: all 0.4s ease;
        padding: 0 20px;
        color: #4a4a4a;
        background-color: white;
    }
    .faq-item.active .faq-answer {
        max-height: 400px;
        padding: 20px;
    }
    .faq-item.active .faq-question span {
        transform: rotate(45deg);
    }
</style>

<section class="faq-section">
    <h1>Frequently Asked Questions</h1>

    @php
        $faqs = [
            [
                'q' => "What is Bouncee’s email verification tool?",
                'a' => "Bouncee is a powerful email verification tool that checks the validity, syntax, domain, and mailbox status of any email address. It helps you reduce bounce rates, block disposable or spam-trap emails, and improve your email marketing deliverability."
            ],
            [
                'q' => "Is Bouncee really offering unlimited free email verifications?",
                'a' => "Yes. For a limited time—until December—we’re offering unlimited free email verifications to help you clean your lists and test our platform without limits. After this promotional period, we’ll offer 500 free verifications every month on our free plan."
            ],
            [
                'q' => "How accurate is Bouncee’s email verification tool?",
                'a' => "Our tool uses multiple layers of verification—syntax checks, MX lookups, SMTP handshakes, and AI-based risk scoring—to achieve over 99% accuracy in detecting valid, invalid, disposable, or risky addresses."
            ],
            [
                'q' => "Can I verify emails in bulk?",
                'a' => "Absolutely. Bouncee supports bulk email verification by letting you upload your entire list in CSV format. You’ll get a downloadable report with each email labeled as valid, invalid, disposable, or risky."
            ],
            [
                'q' => "Does Bouncee provide an API for real-time email verification?",
                'a' => "Yes. Developers can integrate Bouncee directly into signup forms, CRMs, and marketing platforms via our API. We provide clear documentation, SDKs, and a quickstart guide to get you running in minutes."
            ],
            [
                'q' => "What happens to my email data after verification?",
                'a' => "We prioritize data security and privacy. All uploaded lists are encrypted and automatically deleted after a short retention period (as per our data policy). We follow GDPR best practices and never sell your data."
            ],
            [
                'q' => "Do I need a credit card to start?",
                'a' => "No credit card required. Sign up free and start verifying emails instantly."
            ],
            [
                'q' => "Who can benefit from Bouncee?",
                'a' => "Marketers, sales teams, SaaS founders, developers, and agencies can all use Bouncee to clean lists, improve deliverability, and protect their sender reputation."
            ],
            [
                'q' => "How quickly will I see results?",
                'a' => "Single email verifications are instant. Bulk verifications of thousands of emails typically complete within minutes depending on list size."
            ],
            [
                'q' => "How do I get started?",
                'a' => "Just sign up for a free account and start verifying immediately. If you prefer, try our free single-email checker on the homepage—no signup required."
            ],
        ];
    @endphp

    @foreach ($faqs as $faq)
        <div class="faq-item">
            <div class="faq-question">
                <p>{{ $faq['q'] }}</p>
                <span>+</span>
            </div>
            <div class="faq-answer">
                {{ $faq['a'] }}
            </div>
        </div>
    @endforeach
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const faqs = document.querySelectorAll(".faq-item");
        faqs.forEach(faq => {
            faq.querySelector(".faq-question").addEventListener("click", () => {
                faqs.forEach(f => {
                    if (f !== faq) f.classList.remove("active");
                });
                faq.classList.toggle("active");
            });
        });
    });
</script>
