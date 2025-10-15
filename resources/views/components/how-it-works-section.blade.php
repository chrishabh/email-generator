<section class="Htw">
    <div class="container">
        <div class="row justify-content-end align-items-center">
            <!-- Left: Email input box -->
            <div class="col-lg-2 wow fadeInLeft enter-email">
                <div class="card">
                    <input type="text" class="email-input" disabled placeholder="Enter Your Email">
                    <a class="validate-button">VALIDATE</a>
                </div>
            </div>

            <!-- Right: Steps -->
            <div class="col-lg-10">
                <div class="Htw-main">
                    <div class="wow fadeInRight">
                        <h2 class="hiw">How it works</h2>

                        <p><span>1.</span> <span style="font-size: 1.04em; color: #fff; position: relative;">
                            Upload or Paste Emails — Add single addresses or upload a bulk list.
                        </span></p>

                        <p class="odd"><span>2.</span>
                            Run Verification — Our system checks syntax, domains, MX records, and more.
                        </p>

                        <p><span>3.</span>
                            Get Instant Results — See valid, invalid, disposable, and risky addresses clearly labeled.
                        </p>

                        <p class="odd"><span>4.</span>
                            Download Clean List — Export your verified list and start sending with confidence.
                        </p>

                        <div class="cta-btn mt-4">
                            <a href="{{ url('/signup') }}" class="get-started-btn">Get Started</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .Htw {
        background-color: #007bff;
        padding: 80px 0;
        color: #fff;
    }
    .Htw .card {
        border: none;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        width: 230px;
    }
    .Htw .email-input {
        border: none;
        width: 100%;
        padding: 14px;
        font-size: 15px;
        text-align: center;
        color: #333;
    }
    .Htw .validate-button {
        display: block;
        text-align: center;
        background: #1a1a40;
        color: #fff;
        font-weight: 600;
        padding: 12px 0;
        cursor: pointer;
        text-decoration: none;
    }
    .Htw .validate-button:hover {
        background: #5e2ced;
        transition: 0.3s ease;
    }
    .Htw-main {
        padding-left: 40px;
    }
    .Htw .hiw {
        font-weight: 800;
        margin-bottom: 25px;
    }
    .Htw p {
        font-size: 1.05em;
        color: #fff;
        margin-bottom: 15px;
        line-height: 1.6;
    }
    .Htw p span:first-child {
        font-weight: 800;
        font-size: 1.3em;
        margin-right: 10px;
    }
    .Htw .get-started-btn {
        background: #fff;
        color: #1a1a40;
        padding: 12px 28px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        transition: 0.3s;
    }
    .Htw .get-started-btn:hover {
        background: #5e2ced;
        color: #fff;
    }
    @media(max-width: 992px){
        .Htw {
            text-align: center;
        }
        .Htw-main {
            padding-left: 0;
            margin-top: 30px;
        }
        .enter-email {
            justify-content: center;
            display: flex;
        }
    }
</style>
