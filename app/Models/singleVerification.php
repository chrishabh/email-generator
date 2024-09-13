<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class singleVerification extends Model
{
    use HasFactory;
    protected $table ='single_verifications';
    function insertDataAndgetId($array){
        $insertedId = DB::table($this->table)->insertGetId($array);
        return $insertedId;
    }
}
