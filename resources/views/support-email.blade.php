<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Support Request</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background-color: #f7f9fc;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        }
        h2 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .details {
            margin-top: 20px;
        }
        .details p {
            margin: 10px 0;
            line-height: 1.6;
        }
        .footer {
            margin-top: 40px;
            font-size: 13px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📩 New Support Request</h1>

        <p>You have received a new support message through the website.</p>

        <div class="details">
            <p><strong>From:</strong> {{ $email }}</p>
            <p><strong>Message:</strong><br>{{ $messageContent }}</p>
        </div>

        <div class="footer">
            This message was generated automatically from your website bouncee.net.
        </div>
    </div>
</body>
</html>
