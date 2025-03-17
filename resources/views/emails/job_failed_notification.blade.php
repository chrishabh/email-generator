<!DOCTYPE html>
<html>
<head>
    <title>Job Failed Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 600px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 0 auto;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 150px;
        }
        h2 {
            color: #d9534f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f8d7da;
            color: #721c24;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
            text-align: center;
        }
        .mt-20{
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Centered Website Logo -->
        <div class="logo-container">
            <img src="{{ url('assets/logo.png') }}" alt="Company Logo">
        </div>

        <h2 class="logo-container">🚨 Job Failed Notification</h2>

        <!-- Job Details Table -->
        <table>
            <tr>
                <th>File ID</th>
                <td>{{ $fileId }}</td>
            </tr>
            <tr>
                <th>Failed Job ID</th>
                <td>{{ $jobId }}</td>
            </tr>
            <tr>
                <th>Error Message</th>
                <td>{{ $errorMessage }}</td>
            </tr>
        </table>

        <p class="mt-20">Please check the job logs and take necessary actions.</p>

        <p class="footer">This is an automated email. Please do not reply.</p>
    </div>
</body>
</html>
