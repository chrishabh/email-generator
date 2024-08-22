<?php

namespace App\Jobs;

use App\Exports\BulkUploadExport;
use App\Models\BulkUploadEmailFileData;
use App\Models\uploadedAndDownloadFileName;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportVerifiedEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $allUploadedFiles = uploadedAndDownloadFileName::getFileIdsBasedOnCurrentUser($user_id,'verified');
        if(!empty($allUploadedFiles)){
            $currentDate       = Carbon::now()->format('Y-m-d');
            foreach($allUploadedFiles as $key=>$value){
                $verifiedEmailData = BulkUploadEmailFileData::getFileEmails($value->id,$user_id);
                if($verifiedEmailData){
                    $fileName = $value->fileName;
                    $filePath = "public/Bulk Verified Emails/$currentDate/$user_id/$value->id/$fileName";
                    Excel::store(new BulkUploadExport($verifiedEmailData ), $filePath); 
                }
                 
            }
        }
    }
}
