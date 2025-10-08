<?php

namespace App\Http\Controllers\PublicEmailVerifications;
 
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Http\Requests\PublicBulkStartVerificationFormRequest;
use App\Http\Requests\PublicBulkUploadFormRequest;
use App\Models\BulkVerificationApiData;
use App\Models\BulkVerificationApiJob;
use App\Models\User;
use App\Models\UserCredits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmailVerificationController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $email = $request->query('email');
        $emailController = new EmailController();
        $userId = $request->api_key_data->user_id;
        $user = User::find($userId);

        // Call the isValidEmail function from EmailController
        $status = $emailController->isValidEmail($email,true, $userId,$userId);  
        UserCredits::updateCreditsWhenEmailGetsVerify($userId ,1);
        return response()->json([
            "status"=>$status,
            "success"=>true,
            "code" =>200,
            "email"=> $email,
            "domain" => explode('@', $email)[1],
            "user" =>$user->name 
        ],200);
    }

    public function creditInfo(Request $request)
    {
        $userId = $request->api_key_data->user_id;
        $userCredit= UserCredits::getCreditPoint($userId);
        $creditPoints = ($userCredit) ? $userCredit->credits : 0;
        return response()->json([
            "success"=>"true",
            "code" =>200,
            "credits_info"=>[
                "credits_remaining" => $creditPoints??0,
            ],
                
        ],200);
        
    }

    public function bulkVerify(PublicBulkUploadFormRequest $request)
    {
        return response()->json(BulkVerificationApiJob::uploadfile($request), 200);
    }


    public function chkJob(Request $request, $jobId = null)
    {
        
        // Validation
        // $validated = $request->validate([
        //     'apikey' => 'required|string'
        // ]);

        // If jobId is passed via route use it, else get from body
        $jobId = $jobId ?? $request->input('jobId'); 
        $apiKey=  $request['apiKey'];

        if (!$jobId) {
            return response()->json([
                'success' => false,
                'message' => 'Job ID is required'
            ], 400);
        }

        // Fetch the job
        $job = BulkVerificationApiJob::where('job_id', $jobId)
            ->where('api_key', $apiKey)->whereNull('deleted_at')
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found. Invalid jobId'
            ], 400);
        }


        $basicResponse = [
            'success' => true, 
            'status' => $job->status,
            'job_id' => $job->job_id, 
            'created_at' => $job->created_at->format('m/d/Y, h:i:s A'),
            
        ];

        $dataQuery = BulkVerificationApiData::where('file_id', $job->id);
        switch ($job->status) {
            case 'new':
                $total = $dataQuery->count();
                return response()->json(array_merge($basicResponse, [
                    'status'=>'ready',
                    'total' => $total,
                    'message' => 'Job ready for verification. Please begin verification to know the result'
                ]));

            case 'preparing':
                return response()->json(array_merge($basicResponse, [
                    'message' => 'Job is being prepared for verification'
                ]));

            case 'verifying':
                $verifiedCount      = (clone $dataQuery)->whereNotNull('status')->count();
                $pendingCount       = (clone $dataQuery)->whereNull('status')->count(); 
                $deliverableCount   = (clone $dataQuery)->where('status', 'deliverable')->count();
                $undeliverableCount = (clone $dataQuery)->where('status', 'undeliverable')->count();
                $acceptAllCount     = (clone $dataQuery)->where('status', 'accept_all')->count();
                $unknownCount       = (clone $dataQuery)->where('status', 'unknown')->count(); 
                $total              = $dataQuery->count();

                return response()->json(array_merge($basicResponse, [
                    'total' => $total,
                    'verified' => $verifiedCount,
                    'pending' => $pendingCount,
                    'results' => [
                        'deliverable' => $deliverableCount,
                        'undeliverable' => $undeliverableCount,
                        'accept_all' => $acceptAllCount,
                        'unknown' => $unknownCount
                    ],
                    'message' => 'Job is being verified'
                ]));

            case 'failed':
                
                $total = $dataQuery->count();
                return response()->json(array_merge($basicResponse, [
                    'total' => $total,
                    'message' => 'Unable to process your job, The uploaded list contains invalid data.'
                ]));

            case 'cancelled':
                $total = $dataQuery->count();
                return response()->json(array_merge($basicResponse, [
                    'total' => $total,
                    'message' => 'Job is cancelled.'
                ]));

            case 'completed': 

                $verifiedCount      = (clone $dataQuery)->whereNotNull('status')->count();
                $pendingCount       = (clone $dataQuery)->whereNull('status')->count();
                $deliverableCount   = (clone $dataQuery)->where('status', 'deliverable')->count();
                $undeliverableCount = (clone $dataQuery)->where('status', 'undeliverable')->count();
                $acceptAllCount     = (clone $dataQuery)->where('status', 'accept_all')->count();
                $unknownCount       = (clone $dataQuery)->where('status', 'unknown')->count(); 
                $total              = $dataQuery->count();

                return response()->json(array_merge($basicResponse, [
                    'total' => $total,
                    'verified' => $verifiedCount,
                    'pending' => $pendingCount,
                    'results' => [
                        'deliverable' => $deliverableCount,
                        'undeliverable' => $undeliverableCount,
                        'accept_all' => $acceptAllCount,
                        'unknown' => $unknownCount
                    ],
                    'message' => 'Verification completed successfully. Please download the result using /download endpoint.',
                ]));

            default:
                return response()->json(array_merge($basicResponse, [
                    'message' => 'Unknown job status.'
                ]));
        } 
    }

    
    public function deleteJob(Request $request, $jobId = null)
    {
        // Get jobId from route or query
        $jobId = $jobId ?? $request->input('jobId');
        $apiKey = $request->input('apiKey');

        // Validation
        if (!$jobId || !$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Job ID and API key are required.'
            ], 400);
        }

        // Find the job (not already deleted)
        $job = BulkVerificationApiJob::where('job_id', $jobId)
            ->where('api_key', $apiKey)
            ->whereNull('deleted_at')
            ->first();

        if (!$job) {
            return response()->json([
                'success' => false,
                'message' => 'List not found, may be already deleted.'
            ], 400);
        }

        if (in_array($job->status, ['verifying', 'preparing'])) {
            return response()->json([
                'success' => false,
                'result' => 'List is being processed, and cannot be deleted.'
            ], 400);
        }

        // Delete the job (soft delete)
        $job->delete();

        return response()->json([
            'job_id' => $job->job_id,
            'success' => true,
            'message' => 'List will be deleted'
        ], 200);
    }
   
    public function startBulkVerify(PublicBulkStartVerificationFormRequest $request)
    {
        return response()->json(BulkVerificationApiJob::startVerification($request), 200);
    }

    public function testcode()
    {

        $file = Storage::disk('local')->get(BulkVerificationApiJob::getFilePath(3,1));
        
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
                'file_id' => 3,
                'email' => $data['Email'],
                'created_at' => now(),
            ];
            BulkVerificationApiData::createData($insert_data);
        }
    }

    public function downloadJobs(Request $request)
    {
        $jobId = $request->query('jobId');
        $apiKey = $request->query('apiKey');  
        if (empty($jobId) || empty($apiKey)) {
            return response()->json([
                'result' => 'Invalid API Key or jobId',
                'success' => false
            ],400);
        }

        $job = BulkVerificationApiJob::where('job_id', $jobId)->first(); 
        if (!$job) {
            return response()->json([
                'result' => 'Job not found. Invalid jobId',
                'success' => false
            ],400);
        }

        if ($job->api_key !== $apiKey) {
            return response()->json([
                'result' => 'Invalid API Key',
                'success' => false
            ],401);
        }

        switch ($job->status) {
            case 'verifying':
                return response()->json([
                    'result' => 'Job is being verified, please wait until it completes.',
                    'success' => false
                ],400);

            case 'new':
                return response()->json([
                    'result' => 'Job is ready for verification, please start verification and download your results once list verified.',
                    'success' => false
                ],400);

            case 'preparing':
                return response()->json([
                    'result' => 'Job is being prepared for verification, please start verifying and then download you result.',
                    'success' => false
                ],400);

            case 'failed':
            case 'cancelled':
                return response()->json([
                    'result' => 'List cannot be downloaded, The uploaded list contains invalid data.',
                    'success' => false
                ],400);
        }

        if ($job->status !== 'completed' || empty($job->download_file_path)) {
            return response()->json([
                'result' => 'DOWNLOAD-RESTRICTED',
                'success' => false
            ],400);
        }
 
        if ($job->download_file_path) {
            $filePath = storage_path('app/'.$job->download_file_path); 
            if (!file_exists($filePath)) {
                return response()->json([
                    'result' => 'Download file not found',
                    'success' => false
                ],404);
            }   
        }
 
        return response()->download(
            storage_path('app/' . $job->download_file_path),
            $job->download_file_name ?? basename($job->download_file_path)
        );
    }
}
