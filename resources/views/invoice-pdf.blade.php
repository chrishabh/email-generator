<!DOCTYPE html>
<html>

<head>
    <title>Invoice | bouncee</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 30px;
            background: #f7f8fa;
            color: #333;
        }

        .invoice-box {
            max-width: 850px;
            margin: auto;
            background: #fff;
            padding: 40px 50px;
            border: 1px solid #eee;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
        }

        .company-info {
            line-height: 1.5;
        }

        .company-info h2 {
            margin: 0;
            color: #2b2b2b;
        }

        .company-info p {
            margin: 2px 0;
            font-size: 14px;
        }

        .invoice-meta {
            text-align: right;
        }

        .invoice-meta h3 {
            margin: 0;
            color: #555;
        }

        .invoice-meta p {
            margin: 3px 0;
            font-size: 14px;
        }

        .section {
            margin-top: 30px;
        }

        .section h4 {
            border-bottom: 2px solid #ddd;
            padding-bottom: 8px;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px 8px;
        }

        th {
            background: #f5f5f5;
            text-align: left;
        }

        .total-section {
            text-align: right;
            margin-top: 20px;
        }

        .total-section p {
            font-size: 15px;
            margin: 3px 0;
        }

        .total-section strong {
            font-size: 16px;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 13px;
            color: #666;
        }

        .paid-stamp {
            color: #fff;
            background: #28a745;
            padding: 6px 14px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <div class="company-info">
                <h2>Whizzleads Research Services</h2>
                <p>56-A, R K Puram,<br>Govindpuram, Ghaziabad,<br>Uttar Pradesh - 201013</p>
                <p><strong>GST#:</strong> 09BCKPN6035K1Z5</p>
                <p>India: +91-7798625750</p>
            </div>
            <div class="invoice-meta">
                <h3>Invoice # {{ $order_number }}</h3>
                <p><strong>Date:</strong> {{ $invoice_date }}</p>
                <span class="paid-stamp">PAID</span>
            </div>
        </div>

        <div class="section">
            <h4>INVOICE TO</h4>
            <p><strong>{{ $client }}</strong></p>
            <p><strong>GST#:</strong> {{ $client_gst }}</p>
        </div>

        <div class="section">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="width: 150px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['description'] }}</td>
                        <td>{{ $amount_currency }}{{ number_format($item['amount'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <p>Subtotal: {{ $amount_currency }}{{ number_format($subtotal, 2) }}</p>
            <p>GST @ {{ $gst }}{{ number_format($gst_amount, 2) }}</p>
            <p><strong>Total: {{ $amount_currency }}{{ number_format($total, 2) }}</strong></p>
        </div>

        <div class="section">
            <h4>Transaction Details</h4>
            <table>
                <thead>
                    <tr>
                        <th>Transaction Date</th>
                        <th>Gateway</th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $date }}</td>
                        <td>{{ $gateway }}</td>
                        <td>{{ $transaction_id }}</td>
                        <td>{{ $amount_currency }}{{ number_format($total, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>This invoice was created on a computer and is valid without a signature or seal.</p>
        </div>
    </div>
</body>

</html>
