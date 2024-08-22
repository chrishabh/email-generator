<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
 

class BulkUploadExport implements FromView
{
    
    protected $emails;
    public function __construct($emails)
    {
        $this->emails = $emails;
    }
    
    public function view(): View
    {
        return view('exports.verified_emails', [
            'emails' => $this->emails
        ]);
    }

   
    
}