<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>We Miss You at Bouncee!</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            color: #1e293b;
            line-height: 1.6;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 40px;
        }
        .btn {
            display: inline-block;
            background-color: #2563eb;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
        }
        .footer {
            font-size: 12px;
            color: #6b7280;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2 style="margin-bottom: 20px;">We Miss You at Bouncee 👋</h2>

        <p>Hi User,</p>

        <p>We noticed you haven’t been active on the <strong>Bouncee platform</strong> for a while, and we truly miss having you around.</p>

        <p>To welcome you back, we’re offering you <strong>exclusive free access</strong> to Bouncee for a limited time. No fees, no credit card required — just powerful tools at your fingertips.</p>

        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/login') }}" class="btn">Log In and Start Free</a>
        </p>

        <p>If you have any questions or need help getting started again, feel free to reach out. We’re always happy to assist.</p>

        <p>Warm regards,<br>
        The Bouncee Team</p>

        <div class="footer">
            &copy;2025 Bouncee. All rights reserved.  
            <br>
            Need help? Email us at <a href="mailto:support@bouncee.net">support@bouncee.net</a>
        </div>
    </div>
</body>
</html>
