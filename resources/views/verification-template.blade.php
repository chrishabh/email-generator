<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
            color: #51545e;
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
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            color: #333333;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #9aa0ac;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="email-wrapper">
        <div class="email-content">
            <h1>Email Verification Required</h1>
            <p>Hi {{$User}},</p>
            <p>Thank you for signing up! To complete your registration and start using your account, please confirm your email address by clicking the button below:</p>

            <a href="{{$verification_link}}" class="button">Verify Email Address</a>

            <p>If the button above doesn't work, copy and paste the following URL into your web browser:</p>
            <p><a href="{{$verification_link}}" style="color: #007bff;">Copy Link</a></p>

            <p>If you did not create an account, no further action is required.</p>

            <p>Thank you,<br>The Bouncee Team</p>
        </div>

        <div class="footer">
            <p>Need help? <a href="mailto::support@bouncee.net">Contact Support</a></p>
            <p>&copy; 2024 bouncee. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
