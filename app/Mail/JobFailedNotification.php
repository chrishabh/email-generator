<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobFailedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     protected $fileId,$errorMessage,$jobId;

    public function __construct($fileId,$errorMessage,$jobUuid)
    {
        $this->fileId       = $fileId;
        $this->errorMessage = $errorMessage;
        $this->jobId        = $jobUuid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('🚨 Job Failed Notification')->view('emails.job_failed_notification')->
        with([
            'fileId'        => $this->fileId,
            'errorMessage'  => $this->errorMessage,
            'jobId'         => $this->jobId
        ]);
    }
}
