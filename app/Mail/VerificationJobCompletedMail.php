<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationJobCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $userName;
    public $fileName;
    public $totalEmails;
    public $status;
   


    public function __construct($userName, $fileName, $totalEmails, $status)
    {
        $this->userName = $userName;
        $this->fileName = $fileName;
        $this->totalEmails = $totalEmails;
        $this->status = $status;
    }
    
    public function build()
    {
        return $this->subject('Bouncee - Verification Job Completed')
                    ->view('emails.verification_completed')
                    ->with([
                        'userName' => $this->userName,
                        'fileName' => $this->fileName,
                        'totalEmails' => $this->totalEmails,
                        'status' => $this->status,
                    ]);
    }
}
