<!DOCTYPE html>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice | Bouncee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            color: #000;
            font-size: 13px;
        }

        .invoice-box {
            width: 800px;
            margin: 0 auto;
            padding: 25px 40px;
            background: #fff;
        }

        /* === Header Section === */
        .header {
            background: #f1f1f1;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #ccc;
        }

        .company-info {
            line-height: 1.5;
        }

        .company-info h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .company-info p {
            margin: 0;
            font-size: 13px;
        }

        .invoice-meta {
            text-align: right;
            font-size: 13px;
            line-height: 1.4;
        }

        .invoice-meta .paid {
            display: inline-block;
            background: #1a73e8;
            color: #fff;
            padding: 3px 10px;
            border-radius: 2px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-meta p {
            margin: 2px 0;
        }

        /* === Section Headings === */
        h4 {
            font-size: 14px;
            margin-bottom: 6px;
            text-transform: uppercase;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }

        /* === Invoice To Section === */
        .invoice-to {
            margin-top: 25px;
            margin-bottom: 15px;
        }

        .invoice-to p {
            margin: 2px 0;
        }

        /* === Tables === */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f1f1f1;
            font-weight: bold;
        }

        /* === Totals === */
        .total-section {
            text-align: right;
            line-height: 1.6;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .total-section p {
            margin: 0;
        }

        .total-section strong {
            font-weight: bold;
        }

        /* === Footer === */
        .footer {
            text-align: center;
            font-size: 12px;
            color: #555;
            margin-top: 30px;
        }

    </style>
</head>

<body>
    <div class="invoice-box">

        <!-- HEADER -->
        <div class="header">
            <div class="company-info">
                <h2>Whizzleads Research Services</h2>
                <p>56-A, R K Puram, Govindpuram,</p>
                <p>Ghaziabad, Uttar Pradesh - 201013</p>
                <p><strong>GST#:</strong> 09BCKPN6035K1Z5</p>
                <p>India +91-7798625750</p>
            </div>

            <div class="invoice-meta">
                <div class="paid">PAID</div>
                <p><strong>Invoice#:</strong> {{ $order_number }}</p>
                <p><strong>Invoice Date:</strong> {{ $invoice_date }}</p>
                <p><strong>HSN/SAC:</strong> 998315</p>
            </div>
        </div>

        <!-- BILLING INFO -->
        <div class="invoice-to">
            <h4>INVOICE TO:</h4>
            <p><strong>{{ $client }}</strong></p>
            <p>{{ $client_email }}</p>
            <p><strong>GST#:</strong> {{ $client_gst }}</p>
        </div>

        <!-- DESCRIPTION TABLE -->
        <table>
            <thead>
                <tr>
                    <th>DESCRIPTION</th>
                    <th style="width: 160px;">TOTAL</th>
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

        <!-- TOTALS -->
        <div class="total-section">
            <p><strong>SUBTOTAL:</strong> {{ $amount_currency }}{{ number_format($subtotal, 2) }}</p>
            @if($gst > 0)
            <p><strong>GST @  {{ $gst }}%:</strong> {{ $amount_currency }}{{ number_format($gst_amount, 2) }}</p>
            @endif
            <p><strong>TOTAL:</strong> {{ $amount_currency }}{{ number_format($total, 2) }}</p>
        </div>

        <!-- TRANSACTION DETAILS -->
        <h4>TRANSACTION DETAILS</h4>
        <table>
            <thead>
                <tr>
                    <th>TRANSACTION DATE</th>
                    <th>GATEWAY</th>
                    <th>TRANSACTION ID</th>
                    <th>AMOUNT</th>
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

        <!-- FOOTER -->
        <div class="footer">
            <p>This invoice was created on a computer and is valid without a signature or seal.</p>
        </div>
    </div>
</body>

</html>
