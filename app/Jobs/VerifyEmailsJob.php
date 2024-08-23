<?php

namespace App\Jobs;

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
        // $user_id = Auth::user()->id;
        $user_id = 1;      
        $data    = uploadedAndDownloadFileName::getPendingFileDataBasedOnCurrentUser($this->fileId,$user_id,'pending');
        // Once all emails are verified, generate an export file
        $this->verifyEmail($data,$user_id);
        ExportVerifiedEmailsJob::dispatch($this->fileId);
    }
    
    protected function verifyEmail($data,$user_id)
    {
        // Implement your email verification logic here
        if($data){
            $count   = 1;
            $counter = 1;
            $isUpdateData =false;
            foreach($data as $key=>$value){
                $dataArray=[];
                $value->bulk_email_id;
                $isValidEmail = $counter % 2 == 1;
                if($isValidEmail){
                    $dataArray['isValidEmail'] = '1';
                    $dataArray['status']       = 'valid';
                }
                else{
                    $dataArray['isValidEmail'] = '0';
                    $dataArray['status']       = 'invalid';
                }
        
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
