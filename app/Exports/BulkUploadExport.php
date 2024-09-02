<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
 

class BulkUploadExport implements FromView
{
    
    protected $emails;
    protected $isExport;
    public function __construct($emails,$isExport=false)
    {
        $this->emails = $emails;
        $this->isExport = $isExport;
    }
    
    public function view(): View
    {
        return view('exports.verified_emails', [
            'emails' => $this->emails,
            'isExport'=> $this->isExport
        ]);
    }

   
    
}