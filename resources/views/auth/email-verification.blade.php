<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Email Verification | bouncee</title>
    <!-- <script type="text/javascript" async="" src="signup-assets/js/mixpannel-2-latest.min.js"></script> -->
    <script src="signup-assets/js/jquery-min.js"></script>
    <script src="signup-assets/js/script-min.js"></script>
    <script src="signup-assets/js/auth.js" type="text/javascript"></script>

    <link rel="stylesheet" href="signup-assets/css/poppins.css">
    <link rel="shortcut icon" href="assets/bouncee-logo.png" type="image/png">



    <script>
        window.dataLayer = window.dataLayer || [];
        //   function gtag(){dataLayer.push(arguments);}
        //   gtag('js', new Date());

        //   gtag('config', 'UA-79575131-1');
    </script>


</head>

<body class="">


    <main class="sign-container">
        <div class="col-img bgi" style="background-color: #0024a1">

            <div class="sidebar-signup">
                <div>
                    <div class="div-block-148">
                        <h1>Rest Password<br></h1>
                        <p class="signup-intro" style="text-align: center;">Email verification is a powerful tool for
                            your business.<br></p>
                    </div>
                    <div class="div-block-148">
                        <div class="text-block-56">Clean all records matching our intelligent Spam-trap<br> indicators
                        </div>
                        <div class="text-block-56">Checks to see if an email address from a disposable email provider,
                            such as Mailinator exist <br></div>
                        <div class="text-block-56">Any email address containing invalid syntax is <br>instantly removed
                        </div>
                        <div class="text-block-56">Eliminates all email addresses matching our <br>complainers
                            database<br></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-form">
            <div class="col-form--center">
                <a href="/" class="logo img-fluid"><img src="signup-assets/asset/logo.png" alt=""></a>
                @if (session()->has('success'))
                    <div class="alert block-message" id="successBlock">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert block-message" id="alertBlock">
                        {{ session('error') }}
                    </div>
                @endif

                @if (Cookie::has('error_message'))
                    <div class="alert block-message" id="alertBlock">
                        {{ Cookie::get('error_message') }}
                        {{ Cookie::queue(Cookie::forget('error_message')) }}
                    </div>
                @endif
                 
                @error('credentialsError')
                    <div class="alert block-message" id="alertBlock">
                        {{ $message }}
                    </div>
                @enderror  
                <div class="sign-form">
                    <div class="title">
                        Email Verification
                    </div>

                    <form method="POST" accept-charset="utf-8" class="form-inner" id='resetPassword' action="{{ route('verification') }}">
                        @csrf
                        <div class="sign-inp-row">
                            <label for="">Your work email</label>
                            <input type="email" name="emailL" class="{{ $errors->has('emailL') ? 'validation-error' : '' }}" placeholder="Email"  id="email" value={{ old('emailL') }}>
                            <div class="vError" id="emailError">
                                @error('emailL')
                                   {{ $message }}  
                               @enderror
                            </div>
                        </div>

                        <button class="btn fullwidth" type="submit" style="margin-top: 4em;" id="sendVerification">Send Email Verification</button>
                        <div class="sign-form--foot">
                            <a href="/signin">Log In</a> or <a href="/signup">Sign up for new user?</a>
                        </div>
                </div>
                </form>
                <!-- <script>
                    mixpanel.track("View Sing Up Page");
                </script> -->
                <style>
                    .sign-inp-row select {
                        border-radius: 5px;
                        border-color: #161616;
                        margin-bottom: 14px;
                        border-color: black;
                    }

                    .sign-inp-row select {
                        height: 38px;
                        background-color: #fafafb;
                        border: 1px solid #f1f1f5;
                        font-size: 14px;
                        color: #92929d;
                        padding: 0 15px;
                        letter-spacing: 0.1px;
                    }

                    .sign-inp-row select {
                        box-sizing: border-box;
                        width: 100%;
                    }
                </style>

            </div>
        </div>


        </div>
    </main>

    <footer class="mainfooter">

    </footer>


    <div class="preloader">
        <div class="preloader__content">
            <i></i>
            <i></i>
            <i></i>
        </div>
    </div>

    <div id="modal-container"></div>




    <style>
        .alert-success {
            background: linear-gradient(-110deg, #46cda6 0%, #3bb8e1 100%);
        }

        alert-danger {
            background-color: #f44336;
        }

        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
            margin-bottom: 15px;
        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .sign-container .col-img,
        .sign-container .col-form {
            background-color: #ffffff;
        }

        .sign-inp-row input {
            border-radius: 5px;
            border-color: #161616;
        }

        .btn {
            background-color: black;
        }


        .sign-container .col-img,
        .sign-container .col-form {
            background-color: #ffffff;
        }

        .sign-inp-row input {
            border-radius: 5px;
            border-color: #161616;
        }

        .btn {
            background-color: black;
        }

        .sidebar-signup {
            color: white;
            font-size: 20px;
            display: block;
            max-width: 700px;
            margin-top: 40px;
            margin-right: auto;
            padding: 12px 12px 12px 10px;
            margin: 8% 6% 0 10%;

        }

        .div-block-148 {
            width: 100%;
            margin-top: 30px;
            text-align: left;
        }

        .text-block-56 {
            padding-top: 15px;
            padding-bottom: 15px;
            padding-left: 45px;
            background-image: url(https://assets-global.website-files.com/5e4ff204e7b6f80e402d407a/5e9eb746f28ec5564b3ca8fd_check.svg);
            background-position: 0 50%;
            background-size: 30px;
            background-repeat: no-repeat;
            font-size: 18px;
            line-height: 130%;
        }

        .sidebar-signup h1 {
            margin-bottom: 31px;
            font-size: 56px;
            line-height: 110%;
            text-align: center;
        }
    </style>

    <div
        style="background-color: rgb(255, 255, 255); border: 1px solid rgb(204, 204, 204); box-shadow: rgba(0, 0, 0, 0.2) 2px 2px 3px; position: absolute; transition: visibility 0s linear 0.3s, opacity 0.3s linear 0s; opacity: 0; visibility: hidden; z-index: 2000000000; left: 0px; top: -10000px;">
        <div
            style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; z-index: 2000000000; background-color: rgb(255, 255, 255); opacity: 0.05;">
        </div>
        <div class="g-recaptcha-bubble-arrow"
            style="border: 11px solid transparent; width: 0px; height: 0px; position: absolute; pointer-events: none; margin-top: -11px; z-index: 2000000000;">
        </div>
        <div class="g-recaptcha-bubble-arrow"
            style="border: 10px solid transparent; width: 0px; height: 0px; position: absolute; pointer-events: none; margin-top: -10px; z-index: 2000000000;">
        </div>
</body>

</html>
