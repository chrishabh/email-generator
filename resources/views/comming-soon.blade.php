<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            text-align: center;
            color: #333;
        }
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #fff;
        }
        .header {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        .message {
            font-size: 1.2em;
            margin-bottom: 30px;
        }
        .countdown {
            font-size: 3em;
            margin-bottom: 30px;
        }
        .contact {
            font-size: 1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Coming Soon</div>
        <div class="message">We are working hard to bring you something amazing. Stay tuned!</div>
        <div class="countdown" id="countdown">
            <!-- Countdown timer will go here -->
        </div>
        <div class="contact">
            <p>If you have any questions, please contact us at <a href="mailto:support@bouncee.net">support@bouncee.net</a></p>
        </div>
    </div>

    <script>
        // Countdown Timer
        const countdownElement = document.getElementById('countdown');

        // Set the date we're counting down to
        const countDownDate = new Date("2024-12-31T23:59:59").getTime();

        // Update the count down every 1 second
        const x = setInterval(function() {
            const now = new Date().getTime();
            const distance = countDownDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdownElement.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

            if (distance < 0) {
                clearInterval(x);
                countdownElement.innerHTML = "EXPIRED";
            }
        }, 1000);
    </script>
</body>
</html>
