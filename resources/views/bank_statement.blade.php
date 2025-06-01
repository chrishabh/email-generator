<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bank Statement</title>
    <style>
        @page {
        margin-top: 58px;
        margin-bottom: 58px;
        margin-left: 82px;
        margin-right: 82px;
    }

    body {
        font-family: 'Times New Roman', serif;
        font-size: 10pt;
        line-height: 1.4;
    }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .subtitle {
            text-align: center;
            font-size: 11px;
            margin-bottom: 4px;
        }
        .section {
            margin-top: 8px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 4px;
        }
        .details-table {
            width: 100%;
            font-size: 12px;
            border-spacing: 0;
            margin-bottom: 10px;
        }
        .details-table td {
            padding: 0 0;
        }
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
        }
        .transaction-table th, .transaction-table td {
            border: 1px solid #000;
            vertical-align: top;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>

<div class="header" style="width: 100%; margin-bottom: 10px;">
    <div style="text-align: center;">
        <img src="{{ public_path('assets/iob_logo.jpeg') }}" alt="IOB Logo" style="height: 45px;">
    </div>
    <div style="text-align: right; font-size: 12px;">
        <p>Report Generation Date & Time : {{ $generatedOn }}</p>
       
    </div>
    <div style="text-align: left;">
        <p>
            THE MPASSBOOK STATEMENT IS GENERATED FOR SELECTED DATE RANGE BETWEEN<br>
            {{ $dateRange['from'] }} TO {{ $dateRange['to'] }}.
        </p>
    </div>
</div>
<div class="section" style="font-family: 'Times New Roman', serif; font-size: 11pt; margin-top: 5px; margin-bottom: 30px;">
    <div style="font-size: 14px;">CUSTOMER DETAILS</div>

    <table style="width: 100%; border: 1px solid black; border-collapse: collapse; font-size: 11px;">
        <tr>
            <td style="width: 50%; vertical-align: top; border-right: 1px solid black; ">
                <table style="width: 100%;">
                    <tr><td>Customer ID</td><td>: {{ $customer['id'] }}</td></tr>
                    <tr><td>Account No</td><td>: {{ $customer['account'] }}</td></tr>
                    <tr><td>Name of Customer</td><td>: {{ $customer['name'] }}</td></tr>
                    <tr><td>Contact No</td><td>: {{ $customer['contact'] }}</td></tr>
                    <tr><td>Email ID</td><td>: {{ $customer['email'] }}</td></tr>
                    <tr><td>IFS Code</td><td>: {{ $customer['ifsc'] }}</td></tr>
                    <tr><td>Nominee</td><td>: {{ $customer['nominee'] }}</td></tr>
                    <tr><td>Branch Code</td><td>: {{ $customer['branch_code'] }}</td></tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top; padding: 2px;">
                Branch Address :<br>
                {{ $customer['branch_address'] }}<br><br>
                Address of Customer :<br>
                {{ $customer['customer_address'] }}
            </td>
        </tr>
    </table>
</div>


<div class="section">
    <table class="transaction-table">
        <thead>
        <tr>
            <th style="width: 13%;">Date (Value Date)</th>
            <th style="width: 35%;">Particulars</th>
            <th style="width: 12%;">Ref
No./Cheque
No</th>
            <th style="width: 12%;">Transaction Type</th>
            <th style="width: 12%;">Debit(Rs)</th>
            <th style="width: 12%;">Credit(Rs)</th>
            <th style="width: 16%;">Balance(Rs)</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $txn)
            <tr>
                <td>{{ $txn['date'] }}</td>
                <td>{{ $txn['details'] }}</td>
                <td style="text-align: center;">{{ $txn['ref'] }}</td>
                <td style="text-align: center;">{{ $txn['type'] }}</td>
                <td style="text-align: right;">{{ $txn['debit'] }}</td>
                <td style="text-align: right;">{{ $txn['credit'] }}</td>
                <td style="text-align: right;">{{ $txn['balance'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="footer"  style="text-align: left; font-size: 15px;">
    <p>Effective available balance as on {{ $generatedOn }} is INR {{ $finalBalance }}</p>
    <p>**This is a computer generated statement and does not require a signature.</p>
</div>

</body>
</html>
