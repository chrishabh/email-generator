<?php

namespace App\Jobs;

use App\Http\Controllers\EmailController;
use App\Mail\JobFailedNotification;
use App\Mail\VerificationJobCompletedMail;
use App\Models\BulkUploadEmailFileData;
use App\Models\uploadedAndDownloadFileName;
use App\Models\User;
use App\Models\UserCredits;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerifyEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $fileId;
    protected $userId;
    protected $jobUuid;
    public $tries = 3;
    public $timeout = 0; 
    public function __construct($fileId,$userId)
    {
        $this->fileId = $fileId;
        $this->userId = $userId;
        $this->jobUuid = null;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        // $jobId         = $this->job->getJobId();
        if ($this->job) {
            $this->jobUuid = $this->getJobUuid();  
        } 
       
        $user = User::find($this->userId);
        if (!$user) {
            return;
        }

        if($this->jobUuid) {
            uploadedAndDownloadFileName::where('id', $this->fileId)->update(['job_id' => $this->jobUuid]);
        } else {
            \Illuminate\Support\Facades\Log::error(date('Y-M-d H:s:i')." Failed to retrieve job UUID for file ID: {$this->fileId}");
        }
        $user_id = $this->userId;  
        $data    = uploadedAndDownloadFileName::getPendingFileDataBasedOnCurrentUser($this->fileId,$user_id,'pending');
        // Once all emails are verified, generate an export file
        $this->verifyEmail($data,$user_id);
        ExportVerifiedEmailsJob::dispatch($this->fileId,$this->userId);
    }
    
    protected function verifyEmail($data,$user_id)
    {
        // Implement your email verification logic here
        if($data){
            $count         = 0;
            $counter       = 1;
            $isUpdateData  = false;
            $fileName      = $data[0]->fileName; 

            foreach($data as $key=>$value){
                $dataArray = [];
                $status    = EmailController::isValidEmail($value->email,true, $user_id,$this->fileId);
                $dataArray = [
                    'apiStatus'         => $status ? $status:NULL,
                    'status'            => ($status && $status=='Deliverable') ? 'Valid':'Invalid',
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
            $user     = User::find($this->userId);
            $verified = uploadedAndDownloadFileName::getStatus($this->fileId,$user_id);
            Mail::to($user->email)->send(new VerificationJobCompletedMail($user->name, $fileName, $count, $verified->verificationStatus)); 
        }
    }

    protected function deductCreditPoint($user_id,$count){
        return UserCredits::updateCreditsWhenEmailGetsVerify($user_id,$count);
    }

    protected function createfileOfValidEmails($user_id){
        $data    = uploadedAndDownloadFileName::getPendingFileDataBasedOnCurrentUser($user_id,'verified','valid');  
    }

    protected function getJobUuid()
    {
        if ($this->job) {
            $payload = json_decode($this->job->getRawBody(), true);
            return $payload['uuid'] ?? null;
        }
        return null;
    }

    
    public function failed(\Throwable $exception)
    {
        \Illuminate\Support\Facades\Log::error(date('Y-M-d H:i:s') . " Job Failed: {$exception->getMessage()} for File ID: {$this->fileId}");
    
        // Fetch admin emails from the environment variable
        $adminEmails = explode(',', envparam('FAILED_JOB_ADMIN_EMAIL'));
        $validAdminEmails = array_filter($adminEmails, function ($email) {
            return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
        });

        if (!empty($validAdminEmails)) {
            if (!$this->jobUuid) {
                $this->jobUuid = uploadedAndDownloadFileName::where('id', $this->fileId)->value('job_id');
            }
            foreach ($validAdminEmails as $email) {
                // echo $email;
                Mail::to($email)->send(new JobFailedNotification($this->fileId, $exception->getMessage(), $this->jobUuid));
            }
        } else {
            \Illuminate\Support\Facades\Log::error("No valid admin emails found to send job failure notification.");
        }
    }
    

}
