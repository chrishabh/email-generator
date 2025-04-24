<?php

namespace App\Jobs;

use App\Http\Controllers\EmailController;
use App\Mail\VerificationJobCompletedMail;
use App\Models\BulkUploadEmailFileData;
use App\Models\User;
use App\Models\UserCredits;
use App\Models\uploadedAndDownloadFileName;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class VerifyEmailsChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chunkData;
    protected $fileId;
    protected $userId;
    protected $jobUuid;
    protected $isLastChunk;

    public function __construct($chunkData, $fileId, $userId, $jobUuid,$isLastChunk = false)
    {
        $this->chunkData = $chunkData;
        $this->fileId = $fileId;
        $this->userId = $userId;
        $this->jobUuid = $jobUuid;
        $this->isLastChunk = $isLastChunk;
    }

    public function handle()
    {
        $count = 0;
        $fileName = isset($this->chunkData[0]['fileName']) ? $this->chunkData[0]['fileName'] : '';
        $isUpdateData = false;
        // pp($this->chunkData);
        foreach ($this->chunkData as $value) {
            // echo $value->email;
            $status = EmailController::isValidEmail($value->email, true, $this->userId, $this->fileId);
            // echo $status;
            $dataArray = [
                'apiStatus'         => $status ?: null,
                'status'            => ($status == 'Deliverable') ? 'Valid' : 'Invalid',
                'job_email_status'  => 'verified'
            ];
            // pp($value->bulk_email_id);
 
            if (BulkUploadEmailFileData::updateData($dataArray, $value->bulk_email_id)) {
                $isUpdateData = true;
                $count++;
            }
        }

        
         
        if ($this->isLastChunk && $isUpdateData) {
            $allVerified = BulkUploadEmailFileData::getStatus($this->fileId, $this->userId);
            if($allVerified){ 
                if(uploadedAndDownloadFileName::updateData(['verificationStatus' => 'verified'], $this->fileId)){ 
                    $user     = User::find($this->userId);
                    $verified = uploadedAndDownloadFileName::getStatus($this->fileId, $this->userId);
                    Mail::to($user->email)->send(new VerificationJobCompletedMail($user->name, $fileName, $count, $verified->verificationStatus)); 
                    ExportVerifiedEmailsJob::dispatch($this->fileId, $this->userId);
                }  
            } 
        }
        // if ($isUpdateData) {
        //     uploadedAndDownloadFileName::updateData(['verificationStatus' => 'verified'], $this->fileId);
        //     // UserCredits::updateCreditsWhenEmailGetsVerify($this->userId, $count);
        // }

    }
}
