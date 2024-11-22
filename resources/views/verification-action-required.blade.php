<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
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
        .email-body ol {
            padding-left: 20px;
        }
        .email-body ol li {
            margin-bottom: 10px;
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
            <p>We noticed that your email verification process for your bouncee.net account is still pending. Due to a recent technical issue, you may not have received the initial verification email. Please follow these steps to verify your email:</p>
            <ol>
                <li>Click on the link: <a href="https://bouncee.net/verification" target="_blank">https://bouncee.net/verification</a>.</li>
                <li>Enter your registered email address and click on the <strong>"Send Email Verification"</strong> button.</li>
                <li>Check your inbox for the verification email.</li>
                <li>Open the email and click the <strong>"Verify Email Address"</strong> button.</li>
                <li>Once verified, you can log in to your account without any issues.</li>
            </ol>
            <p>If you did not create an account on bouncee.net, you can safely ignore this email.</p>
        </div>
        <div class="email-footer">
            <p>Thank you,<br>bouncee team,</p>
            <p>Need help? <a href="mailto::support@bouncee.net">Contact Support,</a></p>
            <p>&copy; 2024 bouncee. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
