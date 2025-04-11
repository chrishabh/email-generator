<?php

namespace App\Models;

use App\Jobs\PublicBulkApiCsvData;
use App\Jobs\PublicBulkVerificationJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BulkVerificationApiJob extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'job_id',
        'user_id',
        'api_key',
        'file_path',
        'file_name',
        'download_file_path',
        'download_file_name',
        'status',
    ];


    public static function uploadfile($request)
    {
        $file = $request->file('file');
        $userId = $request->api_key_data->user_id;
        $currentDate       = Carbon::now()->format('Y-m-d');

        $originalFilename  = $file->getClientOriginalName();
        $originalFilename  = pathinfo($originalFilename, PATHINFO_FILENAME);
        $path             = "publicBulkUpload/{$currentDate}/{$userId}/{$originalFilename}";

        // Optional: Store file in storage/app/csv_uploads
        $path = $file->store('csv_uploads');

        // Example: Read CSV content
        $filePath   = $file->storeAs('public/',$path); 

        // Optional: Do something with $csvData
        // You can also use Laravel Excel if needed

        do {
            $jobId = Str::uuid()->toString() . Str::random(14);
        } while (BulkVerificationApiJob::where('job_id', $jobId)->exists());

        $csvData = [
            'job_id'    => $jobId,
            'api_key'   => $request->api_key_data->api_key,
            'user_id'   => $userId,
            'file_path' => $filePath,
            'file_name' => $originalFilename,
            'status'    => 'new',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Save the job to the database
        $file_data = BulkVerificationApiJob::create($csvData);
       
        PublicBulkApiCsvData::dispatch($file_data->id,$userId);

        return [
            'success' => true,
            'message' => 'Bulk verification job created successfully.',
            'job_id' => $jobId,
        ];
    }

    public static function getFilePath($fileId, $userId)
    {
        return BulkVerificationApiJob::where('id', $fileId)->where('user_id',$userId)->where('status','new')->first()->file_path;
       
    }

    public static function startVerification($request)
    {
        $file = BulkVerificationApiJob::where('job_id', $request->job_id)->where('user_id',$request->api_key_data->user_id)->where('status','new')->first();

        if(!empty($file)){
            PublicBulkVerificationJob::dispatch($file->id,$request->api_key_data->user_id);
            return [
                'success' => true,
                'message' => 'Job verification will be attempted shortly. Call /status endpoint to know the status of the Job.',
                'job_id' => $file->job_id,
            ];
        }

        return [
            'success' => false,
            'message' => 'Job not found or already in progress.',
            'job_id' => null,
        ];
    }

    public static function updateJobStatus($jobId, $status)
    {
        $job = BulkVerificationApiJob::where('id', $jobId)->first();
        if ($job) {
            $job->status = $status;
            $job->save();
        }
    }
}
