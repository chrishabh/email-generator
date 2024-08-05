<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            color: #721c24;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 500px;
            text-align: center;
        }
        .error-icon {
            font-size: 50px;
            color: #721c24;
        }
        .message {
            font-size: 18px;
            margin: 20px 0;
        }
        .details {
            font-size: 14px;
            color: #555;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #dc3545;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">&#10060;</div>
        <div class="message">Payment Failed!</div>
        <div class="details">
            We encountered an issue while processing your payment. Please try again or contact support if the issue persists.
            <br><br>
            Transaction ID: <strong>#{{$trnasaction_id}}</strong>
            <br><br>
            If you need assistance, please <a href="rishabh.bouncee@yopmail.com">contact us</a>.
        </div>
        <a href="/" class="button">Try Again</a>
    </div>
</body>
</html>
