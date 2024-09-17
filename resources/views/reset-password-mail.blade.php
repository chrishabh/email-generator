<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
            color: #51545E;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f4f7;
            padding: 20px;
        }
        .email-content {
            max-width: 600px;
            background-color: #ffffff;
            margin: 0 auto;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            width: 150px; /* Adjust the size of the logo */
        }
        h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333333;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background-color: #007BFF;
            color: #ffffff;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
            width: 100%;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #9aa0ac;
        }
        .footer a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="email-wrapper">
        <div class="email-content">

            <!-- App Logo -->
            <div class="logo">
                <img src="{{$logo_url}}" alt="bouncee">
            </div>

            <!-- Email Content -->
            <h1>Password Reset Request</h1>
            <p>Hello,</p>
            <p>We received a request to reset your password for your account. If you did not make this request, you can ignore this email.</p>
            <p>Otherwise, Please use the new password below.</p>
            <p>{{$password}}</p>
            <!-- Button -->


           

            <p>Thank you,<br>The {{$app_name}} Team</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>If you didn’t request a password reset, please <a href="{{$support_link}}">{{$support_email}}</a>.</p>
            <p>&copy; {{$year}} {{$app_name}}. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
