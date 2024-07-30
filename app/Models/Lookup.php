<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lookup extends Model
{
    use HasFactory;

    static function getDataByLookupType($type){
        return Lookup::select('lookup_type','lookup_code','lookup_text','sort_order')->where('lookup_type',$type)->whereNull('deleted_at')->get()->toArray();
    }

}
