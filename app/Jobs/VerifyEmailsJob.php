<?php

namespace App\Jobs;

use App\Http\Controllers\EmailController;
use App\Models\BulkUploadEmailFileData;
use App\Models\uploadedAndDownloadFileName;
use App\Models\UserCredits;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class VerifyEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $fileId;
    public function __construct($fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user_id = Auth::user()->id;  
        $data    = uploadedAndDownloadFileName::getPendingFileDataBasedOnCurrentUser($this->fileId,$user_id,'pending');
        // Once all emails are verified, generate an export file
        $this->verifyEmail($data,$user_id);
        ExportVerifiedEmailsJob::dispatch($this->fileId);
    }
    
    protected function verifyEmail($data,$user_id)
    {
        // Implement your email verification logic here
        if($data){
            $count         = 0;
            $counter       = 1;
            $isUpdateData  = false;
            // pp($data);
            foreach($data as $key=>$value){
                $dataArray = [];
                $status    = EmailController::isValidEmail($value->email,true);
                $dataArray = [
                    'apiStatus'         => $status ? strtolower($status):NULL,
                    'status'            => ($status && strtolower($status)=='deliverable') ? 'valid':'invalid',
                    'job_email_status'  => 'verified'
                ];
               
                // $isValidEmail = $counter % 2 == 1;
                // if($isValidEmail){
                //     $dataArray['isValidEmail'] = '1';
                //     $dataArray['status']       = 'valid';
                // }
                // else{
                //     $dataArray['isValidEmail'] = '0';
                //     $dataArray['status']       = 'invalid';
                // }
        
                if(BulkUploadEmailFileData::updateData($dataArray,$value->bulk_email_id)){
                    $isUpdateData = true;
                }else{
                    $isUpdateData=false;
                }

                $counter++;
                $count++;
            }
            if($isUpdateData){
                uploadedAndDownloadFileName::updateData(['verificationStatus'=>'verified'],$value->id);
                $this->deductCreditPoint($user_id,$count);
            }
            
        }
    }

    protected function deductCreditPoint($user_id,$count){
        return UserCredits::updateCreditsWhenEmailGetsVerify($user_id,$count);
    }

    protected function createfileOfValidEmails($user_id){
        $data    = uploadedAndDownloadFileName::getPendingFileDataBasedOnCurrentUser($user_id,'verified','valid');  
    }
}
