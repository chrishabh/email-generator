<?php

namespace App\Jobs;

use App\Models\BulkVerificationApiData;
use App\Models\BulkVerificationApiJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PublicBulkApiCsvData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $fileId;
    protected $userId;
    public $tries = 3;
    public $timeout = 0; 
    public function __construct($fileId,$userId)
    {
        $this->fileId  =  $fileId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Load file from storage
        $file = Storage::disk('local')->get(BulkVerificationApiJob::getFilePath( $this->fileId,$this->userId));
        
        // Convert to lines
        $lines = explode("\n", $file);
        $header = null;

        foreach ($lines as $line) {
            if (trim($line) === '') continue;

            $row = str_getcsv($line);

            if (!$header) {
                $header = $row;
                continue;
            }

            $data = array_combine($header, $row);

            // Insert or update model based on your CSV structure
            $insert_data = [
                'file_id' => $this->fileId,
                'email' => $data['email'],
                'created_at' => now(),
            ];
            BulkVerificationApiData::createData($insert_data);
        }
    
    }
}
