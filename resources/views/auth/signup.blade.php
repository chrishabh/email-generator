<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>SignUp | Bouncee</title>
    <!-- <script type="text/javascript" async="" src="signup-assets/js/mixpannel-2-latest.min.js"></script> -->
    <script src="signup-assets/js/jquery-min.js"></script>
    <script src="signup-assets/js/script-min.js"></script>

    <link rel="stylesheet" href="signup-assets/css/poppins.css">
    <link rel="shortcut icon" href="assets/bouncee-logo.png" type="image/png">


    <script src="signup-assets/js/auth.js" type="text/javascript"></script>
    <!-- <link rel="icon" href="/favicon.ico" type="image/x-icon"> -->


    <script>
        // window.dataLayer = window.dataLayer || [];

        // function gtag() {
        //     dataLayer.push(arguments);
        // }
        // gtag('js', new Date());

        // gtag('config', 'UA-79575131-1');
    </script>


</head>

<body class="">


    <main class="sign-container">
        <div class="col-img bgi" style="background-color: #0024a1">

            <div class="sidebar-signup">
                <div>
                    <div class="div-block-148">
                        <h1>Get started in a minute<br></h1>
                        <p class="signup-intro" style="text-align: center;">Email verification is a powerful tool for
                            your business.<br></p>
                    </div>
                    <div class="div-block-148">
                        <div class="text-block-56">Clean all records matching our intelligent Spam-trap<br> indicators
                        </div>
                        <div class="text-block-56">Checks to see if an email address from a disposable email provider,
                            such as Mailinator exist <br></div>
                        <div class="text-block-56">Any email address containing invalid syntax is<br> instantly removed
                        </div>
                        <div class="text-block-56">Eliminates all email addresses matching our complainers database<br>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-form">
            <div class="col-form--center">
                <a href="" class="logo"><img src="signup-assets/asset/logo.png" alt=""></a>


                <div class="sign-form">
                    <div class="title">
                        Sign Up to your acounts
                    </div>
                    <form method="POST" accept-charset="utf-8" id="signUpForm" action="{{ route('signup') }}">
                        @csrf
                        {{-- <div style="display:none;"><input type="hidden" name="_method" value="POST"></div> --}}
                        <div class="sign-inp-row">
                            <!-- Custom select structure -->

                            <select
                                class="nbemails {{ $errors->has('no_of_email_verification') ? 'validation-error' : '' }}"
                                name="no_of_email_verification" onchange="" onclick="return false;" id=""
                                placehodlder="">
                                <option value="" disabled="" selected="">How many emails do you want to
                                    verify ?</option>
                                @foreach ($dropDownData as $key => $value)
                                    <option value="{{ $value['lookup_text'] }}">{{ $value['lookup_text'] }}</option>
                                @endforeach
                            </select>
                            <div class="vError" id="NES">
                                @error('no_of_email_verification')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="sign-inp-row">
                            <input type="text" name="name" placeholder="Enter your full name"
                                class="{{ $errors->has('name') ? 'validation-error' : '' }}" maxlength="77"
                                id="fullname" value="{{ old('name') }}">

                            <div class="vError" id="nameError">
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="sign-inp-row">
                            <input type="email" name="email" placeholder="Your email address"
                                class="{{ $errors->has('email') ? 'validation-error' : '' }}" maxlength="50"
                                id="username" value="{{ old('email') }}">

                            <div class="vError" id="emailError">
                                @error('email')
                                    {{ $message }}
                                @enderror
                            </div>

                        </div>

                        <div class="sign-inp-row pass pass_field">
                            <input type="password" name="password" placeholder="Type your password (e.g., Passw0rd!)"
                                id="pass_change" class="{{ $errors->has('password') ? 'validation-error' : '' }}"
                                maxlength="25">
                            <div class="icon-eye toggle_pass"></div>

                            <div class="vError" id="passwordError">
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </div>

                        </div>
                        <!-- <p>
                                By signing up, you confirm that you’ve read
                            and accepted our <a href="">User Notice</a> and <a href="">Privacy Policy.</a>
                            </p> -->

                        <button class="btn fullwidth" id="signUpBtn" type="submit" style="margin-top: 4em;">Sign
                            Up</button>
                        <div class="sign-form--foot">
                            <a href="/signin">Already have an account? Log in</a>
                        </div>
                        {{-- </div> --}}
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
