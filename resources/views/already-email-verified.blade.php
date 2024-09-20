<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Already Verified</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            text-align: center;
            color: #333;
        }
        .content h1 {
            font-size: 24px;
            margin: 20px 0;
        }
        .content p {
            font-size: 16px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 12px;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="your-logo-url.png" alt="Company Logo">
        </div>
        <div class="content">
            <h1>Email Already Verified</h1>
            <p>It looks like your email has already been verified. No further action is needed!</p>
            <p>If you believe this is a mistake, please contact our support team.</p>
            <a href="{{ url('/') }}" class="button">Go to Dashboard</a>
        </div>
        <div class="footer">
            <p>&copy; 2024 bouncee. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
