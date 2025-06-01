<?php

namespace App\Imports;

use App\Models\BankTransaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BankTransactionsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
    //    print_r($row);
    //    die();
        // Clean and parse date
        $rawDate = explode(' ', $row['date_value_date'])[0]; // e.g. "01-Jan-2025"
        $date = \Carbon\Carbon::createFromFormat('d-M-Y', $rawDate)->format('Y-m-d');

        // Parse and clean money fields
        $debit = $row['debitrs'] !== '-' ? floatval(str_replace(',', '', $row['debitrs'])) : null;
        $credit = $row['creditrs'] !== '-' ? floatval(str_replace(',', '', $row['creditrs'])) : null;
        $balance = floatval(str_replace(',', '', $row['balance_rs']));
       
        return new BankTransaction([
            'value_date' => $row['date_value_date'],
            'particulars' => $row['particulars'],
            'reference_no' => $row['ref_nocheque_no'],
            'transaction_type' => $row['transaction_type'],
            'debit' =>  $row['debitrs'] == '-' ? null : $debit,
            'credit' => $row['creditrs'] == '-' ? null : $credit,
            'balance' => $row['balance_rs'] == '-' ? null : $balance,
        ]);
    }
}
