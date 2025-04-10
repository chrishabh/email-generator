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

class PublicBulkVerificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $fileId;
    protected $userId;
    public $timeout = 1200; 

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
        BulkVerificationApiJob::updateStatus($this->fileId, 'in-progress');
        $email_data = BulkVerificationApiData::getData($this->fileId);

        if ($email_data->isEmpty()) {
            BulkVerificationApiJob::updateStatus($this->fileId, 'completed');
            return;
        }
        foreach ($email_data as $data) {
            $email = $data->email;
            $userId = $this->userId;
            $response = $this->verifyEmail($email);
            BulkVerificationApiData::updateData($data->id, $this->fileId, $email, $response['status'] ?? "Unkown", json_encode($response));
        }
        BulkVerificationApiJob::updateStatus($this->fileId, 'completed');
    }

    public function verifyEmail($email)
    {
        $apiUrl = envparam('BOUNCEE_API_URL_PUBLIC_BULK_VERIFICATION');
        $apiKey = envparam('BOUNCEE_API_KEY');
        $response = Http::withHeaders([
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
        ])->get("$apiUrl=$email");
        
        // Extract response body and HTTP status code
        $responseBody = $response->json();

        return $responseBody;
    }
}
