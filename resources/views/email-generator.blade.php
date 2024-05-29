<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Generator</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url(/email-background-images-1.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            padding: 20px;
            border-radius: 10px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <h3 class="card-title text-center">Email Generator</h3>
                    <form action="{{ route('generateEmail') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" id="first_name" name="first_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" id="last_name" name="last_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="domain">Domain:</label>
                            <input type="text" id="domain" name="domain" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-custom btn-block">Generate Email</button>
                    </form>

                    @if(session('validEmails'))
                        <div class="mt-4">
                            <h5>Valid Email Addresses:</h5>
                            <ul class="list-group">
                                @foreach(session('validEmails') as $email)
                                    <li class="list-group-item">{{ $email }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
