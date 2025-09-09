<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Thank you | bouncee</title>
  <meta name="description" content="Thanks for signing up to bouncee." />
  <script src="signup-assets/js/jquery-min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>

    <script src="signup-assets/js/script-min.js"></script>
    <script src="signup-assets/js/auth.js" type="text/javascript"></script>

    <link rel="stylesheet" href="signup-assets/css/poppins.css">
    <link rel="shortcut icon" href="assets/bouncee-logo.png" type="image/png">
</head>

<body class="">


    <main class="sign-container">
        <div class="col-img bgi" style="background-color: #0024a1">

            <div class="sidebar-signup">
                <div>
                    <div class="div-block-148">
                        <h1>Welcome to bounce<br></h1>
                        <p class="signup-intro" style="text-align: center;">Your account has been created successfully. You’re ready to experience smarter email verification and management.<br></p>
                    </div>
                    <div class="div-block-148">
                        <div class="text-block-56">Enjoy lightning-fast email verification powered by bouncee’s intelligent system
                        </div>
                        <div class="text-block-56">Instant access to your dashboard for real-time results <br></div>
                        <div class="text-block-56">Stay secure with enterprise-grade data protection
                        </div>
                        <div class="text-block-56">Grow your business with cleaner, verified email lists</div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-form">
  <div class="col-form--center">
    <a href="/" class="logo img-fluid">
      <img src="signup-assets/asset/logo.png" alt="">
    </a>

    <h2 class="text-2xl font-semibold mb-4">Thank you for signing up!</h2>
    <p class="text-slate-600 mb-8">You can now log in and start using bouncee’s services.</p>

    <a href="/signin"
       class="flex items-center justify-center w-full px-5 py-3 rounded-lg bg-black text-white font-medium shadow hover:bg-slate-800">
       Go to Sign-In
    </a>

    <div class="mt-6 text-sm text-slate-500">
      Need help?
      <a href="mailto:support@bouncee.net" class="text-blue-700 hover:underline">Contact support</a>
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
            padding: 20px;
            background-color: #4CAF50; /* Success green */
            color: white;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            line-height: 1.5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        alert-danger {
            padding: 20px;
            background-color: #f44336;
            color: white;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            line-height: 1.5;
        }

        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            line-height: 1.5;
        }

        .alert .close-btn {
            float: right;
            font-size: 20px;
            font-weight: bold;
            color: white;
            cursor: pointer;
        }

        .alert .close-btn:hover {
            color: #ffcccb;
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
    </>

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









