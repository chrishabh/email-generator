<!DOCTYPE html>
<html>
<head>
    <title>Verification Job Completed</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #121212; padding: 20px; color: #fff;">
    <div style="max-width: 600px; margin: 0 auto; background: #1e1e1e; padding: 20px; border-radius: 8px;">
        <h2 style="color: #7da6ff; text-align: center;">Verification Job Completed</h2>
        <p>Dear {{ $userName }},</p>
        <p>Your uploaded list has finished processing. You can now download the verification results.</p>
        <p><strong>File Name:</strong> <span style="color: #ffa500;">{{ $fileName }}</span></p>
        <p><strong>Total Emails:</strong> {{ $totalEmails }}</p>
        <p><strong>Status:</strong> <span style="color: {{ $status == 'verified' ? 'green' : 'red' }};">{{ $status }}</span></p>
        <p>To check the verification result, please click below.</p>
        <p style="text-align: center;">
            <a href="{{ url('/signin') }}" 
               style="display: inline-block; background-color: #217278; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
               Click to Login
            </a>
        </p>
        <p>Thank you for using Bouncee email validation service.</p>
        <hr style="border: 1px solid #333;">
        <p style="text-align: center; font-size: 12px; color: #bbb;">
            Have any questions? Contact <a href="mailto:support@bouncee.net" style="color: #7da6ff;">support@bouncee.net</a> 
            <br> &copy; Bouncee 2025
        </p>
    </div>
</body>
</html>