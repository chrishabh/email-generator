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

    public function collection(Collection $rows)
    {
        // Validate headers
        $headerRow   = $rows->first();
        $headers     = array_keys($headerRow->toArray());
        $isInsertAll = false;
        if ($this->validateHeaders($headers)) {
            // $rows->shift();
            $insertArray=[];
            foreach ($rows as $key=>$row) {
                $arr = array();
                $data = $row->toArray(); 
                // Validate each row data
                $validator = Validator::make($data, [
                    'firstname' => 'required',
                    'lastname'  => 'required',
                    'domain'    => 'required',
                ]);
    
                if($validator->fails()) {
                    Log::error('Validation failed: ', $validator->errors()->toArray());
                    continue; // Skip the row if validation fails
                }
     
                // Save the valid data to the database
                $arr =array(
                    'firstName'  => $data['firstname'],
                    'lastName'   => $data['lastname'],
                    'domain'     => $data['domain'],
                    'importedBy' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                );
                array_push($insertArray,$arr);
            }

            if(DB::table('bulk_upload_email_file_data')->insert($insertArray)){
                $isInsertAll =true;
            }else{
                $isInsertAll =false; 
            }
             
            return $isInsertAll;
        } else {
            throw new ValidationException('Invalid header row. Please ensure your file has the correct headers.');
        }
    }
    
 
    protected function validateHeaders(array $headers)
    {
        return $headers === $this->validHeaders;
    }

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
