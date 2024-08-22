<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BulkUploadEmailFileData extends Model
{
    use HasFactory;
    protected $table = 'bulk_upload_email_file_data';
     
    public static function getBulkData($userId){
        return self::select('bulk_upload_email_file_data.created_at','b.fileName',DB::raw('COUNT(*) as total'))->leftJoin('uploaded_and_download_file_names as b', 'bulk_upload_email_file_data.importedBy', '=', 'b.user_id')->where('importedBy',$userId)->whereNull('deleted_at')->where('type','bulk')->groupBy('fileName','created_at')->get()->toArray();
    }

    public static function getDataBasedOnVerificationStatus($status){
        self::where('')->where('status',$status)
        ->get();
    }

    static function updateData($array,$id){
        return self::where('id',$id)->update($array);
    }

    static function getFileEmails($fileId,$userId){
        return self::select('email')->where('file_id',$fileId)->where('importedBy',$userId)->where('status','valid')->where('type','bulk')->get()->toArray();
    }

    static function getCountOfValidAndInvalidEmails($fileId,$userId){
        return self::select('status', DB::raw('count(*) as total_count'))->where('importedBy',$userId)->where('file_id',$fileId)->where('type','bulk')
            ->groupBy('status')
            ->get()->toArray();
    }
    
}
