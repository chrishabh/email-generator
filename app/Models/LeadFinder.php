<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LeadFinder extends Model
{
    use HasFactory;
    protected $table = 'lead_finders';

    function leadFinderPCEmailLogs(){
        return $this->hasMany(LeadFinderPCEmailLogs::class);
    }

    function insertDataAndgetId($array){
        $insertedId = DB::table($this->table)->insertGetId($array);
        return $insertedId;
    }
}
