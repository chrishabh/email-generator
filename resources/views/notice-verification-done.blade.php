<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Completed</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333333;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #0073e6;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 20px;
            line-height: 1.6;
            color: #555555;
        }
        .email-body p {
            margin: 0 0 15px;
        }
        .email-body a.button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            font-size: 16px;
            color: #ffffff;
            background-color: #0073e6;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .email-body a.button:hover {
            background-color: #005bb5;
        }
        .email-footer {
            text-align: center;
            font-size: 14px;
            color: #888888;
            padding: 15px;
            background-color: #f9fafc;
        }
        .email-footer a {
            color: #0073e6;
            text-decoration: none;
        }
        .email-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h1>bouncee</h1>
        </div>
        <div class="email-body">
            <p>Dear User,</p>
            <p>We’re happy to inform you that your email verification has been completed successfully by bouncee.net.</p>
            <p>You can now log in to your bouncee account without any issues.</p>
            <p>Please click the button below to access your account:</p>
            <a class="button" href="https://bouncee.net/signin" target="_blank">Login to bouncee</a>
            <p>If you did not create an account on bouncee.net, you can safely ignore this email or report it to us at support@bouncee.net.</p>
        </div>
        <div class="email-footer">
            <p>Thank you,<br>bouncee team</p>
            <p>Need help? <a href="mailto:support@bouncee.net">Contact Support</a></p>
            <p>&copy; 2025 bouncee. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
