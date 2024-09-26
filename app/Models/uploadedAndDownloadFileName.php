<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class uploadedAndDownloadFileName extends Model
{
    use HasFactory;
    protected $table ='uploaded_and_download_file_names';

    function insertDataAndgetId($array){
        $insertedId = DB::table($this->table)->insertGetId($array);
        return $insertedId;
    }
    
    static function updateData($array,$id){
        return self::where('id',$id)->update($array);
    }

    public static function getPendingFileDataBasedOnCurrentUser($file_id,$user_id,$verificationStatus,$status=''){
        return DB::table('uploaded_and_download_file_names as ud')->select('ud.fileName','ud.id','bu.apiStatus','bu.file_id','bu.id as bulk_email_id', 'bu.email', 'bu.isValidEmail', 'bu.type', 'bu.status')
            ->leftJoin('bulk_upload_email_file_data as bu', function($join) {
            $join->on('ud.id', '=', 'bu.file_id')
                ->on('ud.user_id', '=', 'bu.importedBy'); 
        })->where('ud.user_id', '=',$user_id)->where('ud.id',$file_id)->where('bu.type','bulk')->where('ud.verificationStatus',$verificationStatus)
        ->when($status === 'valid', function ($query) use ($status) {
            return $query->where('bu.status', $status);
        })->get();
    }

    static function getFileIdsBasedOnCurrentUser($file_id,$user_id,$verificationStatus){
        return DB::table('uploaded_and_download_file_names')->select('fileName','id','verificationStatus')->where('verificationStatus',$verificationStatus)->where('id',$file_id)->where('user_id',$user_id)->get();
    }

    public static function getAllData($file_id='',$user_id,$searchContent=''){
        // return DB::table('uploaded_and_download_file_names as ud')->select('ud.fileName','ud.id','bu.file_id','bu.id as bulk_email_id', 'bu.email', 'bu.isValidEmail', 'bu.type', 'bu.status')
        //     ->leftJoin('bulk_upload_email_file_data as bu', function($join) {
        //     $join->on('ud.id', '=', 'bu.file_id')
        //         ->on('ud.user_id', '=', 'bu.importedBy'); 
        // })->where('ud.user_id', '=',$user_id)->where('bu.type','bulk')->get();

        // $query = "SELECT * FROM uploaded_and_download_file_names as f left join bulk_upload_email_file_data ba on f.id=ba.file_id and f.user_id=ba.importedBy WHERE type='bulk' AND f.user_id=1";
        $query= DB::table('uploaded_and_download_file_names')->where('user_id',$user_id);
        if(!empty($searchContent)){
            $query->where(function ($query) use ($searchContent) {
                $query->where('fileName', 'LIKE', "%$searchContent%")->orWhere('downloadFileName', 'LIKE', "%$searchContent%");
            });
        }
 
        if($file_id){
            $query->where('id',$file_id);
        }

        return $query->orderByDesc('id')->get()->toArray();

    }
    public static function getDownloadPath($file_id,$user_id){
          return DB::table('uploaded_and_download_file_names')->where('user_id',$user_id)->where('id',$file_id)->where('verificationStatus','verified')->first();

    }

    static function getDataFromTable($fileId,$user_id,$verificationStatus){
        return self::where('id',$fileId)->where('user_id',$user_id)->where('verificationStatus',$verificationStatus)->first()->toArray();
    }

    static function getStatusOfEmailVerification($fileId){
        $query = "SELECT uf.id,
        count(bu.id) as total_emails,
        SUM(CASE WHEN bu.job_email_status='verified' THEN 1 ELSE 0 END) as total_verified_emails,
        SUM(CASE WHEN bu.job_email_status!='verified' AND bu.job_email_status IS NULL THEN 1 ELSE 0 END) as total_not_verified_emails
        from uploaded_and_download_file_names uf left join bulk_upload_email_file_data bu on bu.file_id=uf.id where uf.id=$fileId GROUP BY uf.id";
        $result  = DB::select($query);
        return $result;
    }



     
}
