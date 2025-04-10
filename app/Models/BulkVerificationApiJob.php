<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BulkVerificationApiJob extends Model
{
    use HasFactory;


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
       

        return [
            'success' => true,
            'message' => 'Bulk verification job created successfully.',
            'job_id' => $jobId,
        ];
    }

}
