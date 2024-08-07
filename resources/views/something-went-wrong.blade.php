<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Something Went Wrong |Bouncee</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #ff5f6d, #ffc371);
            font-family: 'Arial', sans-serif;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .error-container h1 {
            font-size: 50px;
            margin: 0;
            color: #333;
        }
        .error-container p {
            font-size: 20px;
            margin: 20px 0;
            color: #666;
        }
        .error-container a {
            text-decoration: none;
            color: white;
            background: #ff5f6d;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        .error-container a:hover {
            background: #ff4040;
        }
        .error-container img {
            width: 100px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <img src="assets/error.png" alt="Error Icon">
        <h1>Oops!</h1>
        <p>Something went wrong. Please try again later.</p>
        <a href="/">Go Back Home</a>
    </div>
</body>
</html>
