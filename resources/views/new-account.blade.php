<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: rgb(10, 93, 170);
            color: white;
            padding: 20px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px;
        }

        .content p {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .content .info-box {
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box strong {
            display: block;
            margin-bottom: 5px;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            font-size: 14px;
        }

        .footer a {
            color: white;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>New Account Notification</h1>
        </div>

        <div class="content">
            <p>Hello Admin,</p>
            <p>A new account has just been created on the system. Here are the details:</p>

            <div class="info-box">
                <strong>Account Name:</strong> {{$User}}
                <strong>Email Address:</strong> {{$account_email }}
                <strong>Date of Creation:</strong> {{$account_creation_date }}
            </div>

            <p>If you need to review the account, please log in to the admin dashboard.</p>

            <p>
