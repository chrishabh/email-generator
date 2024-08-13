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
        return self::select('created_at','fileName',DB::raw('COUNT(*) as total'))->where('importedBy',$userId)->whereNull('deleted_at')->where('type','bulk')->groupBy('fileName','created_at')->get()->toArray();
    }
    
}
