<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\BankTransactionsImport;
use App\Models\BankTransaction;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class BankStatementController extends Controller
{
    

    public function generate()
    {
        $customer = [
            'id' => '51783787',
            'account' => '130501000021370',
            'name' => 'RISHABH CHOUDHARY',
            'contact' => '+918527954029',
            'email' => 'ch.rishabh8527@gmail.com',
            'ifsc' => 'IOBA0001305',
            'nominee' => 'YES',
            'branch_code' => '1305',
            'branch_address' => 'A , 172 GROUND FLOOR, PREET VIHAR ,PATPARGANJ,, PREET VIHAR, NCT OF DELHI, 110092',
            'customer_address' => 'R,O,72 PANDIT CHOWK MANDAWALI ,MANDAVALLI ,NCT OF DELHI ,110092'
        ];
    
        $dateRange = ['from' => '01-01-2025', 'to' => '31-05-2025'];
        $generatedOn = now()->format('d-m-Y H:i:s');
    
        $transactions = BankTransaction::orderBy('id','asc')
        ->get()
        ->map(function ($txn) {
            return [
                'date' => $txn->value_date,
                'details' => $txn->particulars,
                'ref' => $txn->reference_no,
                'type' => $txn->transaction_type,
                'debit' => $txn->debit ? $txn->debit : '-',
                'credit' => $txn->credit ? $txn->credit : '-',
                'balance' => $txn->balance ? $txn->balance : '-',
            ];
        });
    
        $finalBalance = '311664.98';
    
        $pdf = Pdf::loadView('bank_statement', compact('customer', 'dateRange', 'generatedOn', 'transactions', 'finalBalance'));
        return $pdf->download('bank_statement_exact.pdf');
    }

    public function importTransactions(Request $request)
{
    $path = storage_path('app/BankTransaction.xlsx');

    if (!file_exists($path)) {
        return response()->json(['error' => 'File not found at ' . $path], 404);
    }

    Excel::import(new BankTransactionsImport, $path);

    return response()->json(['success' => 'Transactions imported successfully!']);
}

}
