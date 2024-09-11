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
}
