<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LeadFinderPCEmailLogs extends Model
{
    use HasFactory;
    protected $table    = 'lead_finder_p_c_email_logs';
    protected $hidden = ['created_at', 'deleted_at','updated_at'];

    public function leadFinder(){
        return $this->belongsTo(LeadFinder::class,'lead_finder_id');
    }
    function insertDataAndgetId($array,$id=null){
        if(!empty($id))  $status = LeadFinderPCEmailLogs::where('id',$id)->update($array);
        else
        $status = LeadFinderPCEmailLogs::insert($array);
        return $status;
    }

    public static function getEmailDataBasedOnId($emailId,$fileId){
        return LeadFinderPCEmailLogs::select('lead_finder_id','email','status','id')->where('id',$emailId)->where('lead_finder_id',$fileId)->first()->toArray();
    }

    public static function getLastIdOfPCTable($fileId){
        $data  =  LeadFinderPCEmailLogs::select('id')->where('lead_finder_id',$fileId)->orderBy('id', 'desc')->first(['id']);
        $id    =  null;
        if(!empty($data)){
            $id = $data['id'];
        }
        return $id;
    }
}
