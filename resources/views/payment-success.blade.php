<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success | bouncee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
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
        .success-icon {
            font-size: 50px;
            color: #28a745;
        }
        .message {
            font-size: 18px;
            margin: 20px 0;
        }
        .details {
            font-size: 14px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">&#10004;</div>
        <div class="message">Payment Successful!</div>
        <div class="details">
            Thank you for your payment. Your transaction was processed successfully.
            <br><br>
            Transaction ID: <strong>#{{$trnasaction_id}}</strong>
            <br>
            Amount: <strong>${{$amount_paied/100}}</strong>
            <br><br>
            If you have any questions, feel free to <a href="support@bouncee.net">contact us</a>.
        </div>
        <a href="/pricing" class="button">Return to Homepage</a>
    </div>
</body>
</html>
