<!DOCTYPE html>
<html>

<head>
    <title>Invoice | Bouncee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            color: #000;
            font-size: 14px;
        }

        .invoice-box {
            width: 800px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
        }

        .company-info {
            width: 60%;
            line-height: 1.4;
        }

        .company-info h2 {
            font-size: 20px;
            margin: 0 0 5px 0;
        }

        .company-info p {
            margin: 0;
        }

        .invoice-meta {
            width: 35%;
            text-align: right;
            line-height: 1.4;
        }

        .invoice-meta h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }

        .invoice-meta p {
            margin: 0;
        }

        .paid-stamp {
            font-weight: bold;
            font-size: 14px;
            margin-top: 5px;
        }

        .section {
            margin-top: 25px;
        }

        .section h4 {
            font-size: 15px;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            font-weight: bold;
        }

        .total-section {
            text-align: right;
            margin-top: 20px;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 13px;
        }

        .spacer {
            height: 15px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">

        <div class="header">
            <div class="company-info">
                <h2>Whizzleads Research Services</h2>
                <p>56-A, R K Puram, Govindpuram</p>
                <p>Ghaziabad, Uttar Pradesh - 201013</p>
                <p><strong>GST#:</strong> 09BCKPN6035K1Z5</p>
                <p>India: +91-7798625750</p>
            </div>

            <div class="invoice-meta">
                <h3>Invoice # {{ $order_number }}</h3>
                <p>Date: {{ $invoice_date }}</p>
                <p class="paid-stamp">PAID</p>
            </div>
        </div>

        <div class="section">
            <h4>INVOICE TO</h4>
            <p><strong>{{ $client }}</strong></p>
            <p>{{ $client_email }}</p>
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
            <p>SUBTOTAL: {{ $amount_currency }}{{ number_format($subtotal, 2) }}</p>
            <p>GST @ {{ $gst }}%: {{ $amount_currency }}{{ number_format($gst_amount, 2) }}</p>
            <p><strong>TOTAL: {{ $amount_currency }}{{ number_format($total, 2) }}</strong></p>
        </div>

        <div class="section">
            <h4>TRANSACTION DETAILS</h4>
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
