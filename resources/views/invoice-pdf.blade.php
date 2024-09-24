<!DOCTYPE html>
<html>

<head>
    <title>Invoice | bouncee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 60px;
        }

        .invoice-header img {
            max-height: 80px;
        }

        .invoice-details {
            margin-bottom: 30px;
        }

        .invoice-details p {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f4f4f4;
            text-align: left;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <div>
                <h2>Order # {{ $order_number }}</h2>
                <p>Date: {{ $date }}</p>
            </div>
            <!-- <div class="logo">
                <img src="{{  asset('assets/logo.png')  }}" alt="Company Logo">
            </div> -->
        </div>

        <div class="invoice-details">
            <p><strong>Billed To:</strong> {{ $client }}</p>
            <p><strong>From:</strong> {{ $company }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $k=>$value)
                <tr>
                    <td>{{ $value['description'] }}</td>
                    <td>${{ number_format($value['amount'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <h3 class="total">Total: ${{ number_format($total, 2) }}</h3>
    </div>

</body>

</html>