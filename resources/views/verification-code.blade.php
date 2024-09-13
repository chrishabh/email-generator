<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: whitesmoke; 
            color: #ffffff;
            text-align: center;
            padding: 20px 0;
        }
        .header h1 {
            margin: 0;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            color: #333333;
        }
        .code {
            display: inline-block;
            background-color: #f0f0f0;
            padding: 10px 20px;
            font-size: 20px;
            letter-spacing: 4px;
            margin: 20px 0;
            border-radius: 4px;
            border: 1px solid #cccccc;
        }
        .footer {
            background-color: #eeeeee;
            color: #555555;
            text-align: center;
            padding: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style = "text-align: center;">Verification Code</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>Thank you for registering with us. Please use the following verification code to complete your Sign-In:</p>
            <div class="code">{{$otp_code}}</div>
            <p>If you did not request this code, please ignore this email.</p>
        </div>
        <div class="footer">
            &copy; 2024 <a rel="nofollow" href="https://bouncee.net">bouncee</a>. All rights reserved.
        </div>
    </div>
</body>
</html>
