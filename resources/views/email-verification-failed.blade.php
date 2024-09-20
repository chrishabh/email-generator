<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Failed</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f2f4f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        .logo {
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
            height: auto;
        }
        .title {
            font-size: 26px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .message {
            font-size: 16px;
            color: #333333;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            color: #ffffff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <!-- Optional: Place a logo here -->
            <img src="{{ asset('assets/logo.png') }}" alt="Company Logo">
        </div>
        <div class="title">Email Verification Failed</div>
        <div class="message">
            We were unable to verify your email address. Please check the link and try again.
            <br>
             <a style="margin-top: 3em;" href="{{ url('/verification') }}" class="button">Request a New Verification Email</a>.
        </div>
        <div class="footer">
            &copy; 2024 bouncee. All rights reserved.
        </div>
    </div>
</body>
</html>
