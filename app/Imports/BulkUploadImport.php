<?php

namespace App\Imports;

use App\Models\BulkUploadEmailFileData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth; 
use Maatwebsite\Excel\Concerns\ToModel;  
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToArray;

class BulkUploadImport implements ToCollection, WithHeadingRow
{
    protected $validHeaders = [
        'firstname',
        'lastname',
        'domain'
    ];
    protected $requestData;
    public function __construct($data){
        $this->requestData = $data;
    }

    public function collection(Collection $rows)
    {
        // Validate headers
        // $headerRow   = $rows->first();
        // $headers     = array_keys($headerRow->toArray());
        // $isInsertAll = false;
        // if($rows) {
        //     // $rows->shift();
        //     $insertArray=[];
        //     foreach ($rows as $row) {
        //         $arr = array();
        //         pp($row);
        //         // $data = $row->toArray(); 
        //         $email = $row[0] ?? null;
                 
        //             pp($email);
        //         // Save the valid data to the database
        //         // $arr =array(
        //         //     'firstName'  => $data['firstname'],
        //         //     'lastName'   => $data['lastname'],
        //         //     'domain'     => $data['domain'],
        //         //     'importedBy' => '1',
        //         //     'created_at' => Carbon::now(),
        //         //     'updated_at' => Carbon::now(),
        //         // );
        //         array_push($insertArray,$arr);
        //     }

        //     // if(DB::table('bulk_upload_email_file_data')->insert($insertArray)){
        //     //     $isInsertAll =true;
        //     // }else{
        //     //     $isInsertAll =false; 
        //     // }

        //     $currentDate = Carbon::now()->format('Y-m-d');
        //     $timestamp = Carbon::now()->format('Ymd_His'); 
        //     // Define the user ID
        //     $userId = Auth::user()->id;
        //     $originalFilename = pathinfo($this->requestData->getClientOriginFileName(),PATHINFO_FILENAME);
        //     $extension        = $this->requestData->getClientOriginalExtension();
        //     $fileName         = "{$originalFilename}_{$currentDate}_{$timestamp}.{$extension}";
        //     $path             = "bulkUpload/{$currentDate}/{$userId}/{$fileName}";
        //     $filePath         = $this->requestData->storeAs('public/' . $path);

        //     return true;
        // }else {
        //     throw new ValidationException('Invalid header row. Please ensure your file has the correct headers.');
        // }

        foreach ($rows as $index => $row) {
            // Skip the header row if it exists
             echo $row;
            
        }
        pp('s');

    }
    
 
    // protected function validateHeaders(array $headers)
    // {
    //     return $headers === $this->validHeaders;
    // }

    // public function validateHeaders(array $headers)
    // {
    //     foreach ($this->expectedHeaders as $index => $expectedHeader) {
    //         if (isset($headers[$index]) && $headers[$index] !== $expectedHeader) {
    //             \Illuminate\Support\Facades\Log::error('Header mismatch:', [
    //                 'expected' => $expectedHeader,
    //                 'actual' => $headers[$index]
    //             ]);
    //             throw new \Exception('Header mismatch. Expected headers do not match.');
    //         }
    //     }
    // }
}
