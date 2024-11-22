<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchLog extends Model
{
    use HasFactory;

    public static function batchStarted($batch_type)
    {
        $data = [
            'batch_type' => $batch_type,
            'started_at' => Carbon::now(),
        ];

        return BatchLog::insertGetId($data);
    }

    public static function batchEnded($id,$batch_type)
    {
        return BatchLog::where('id',$id)->where('batch_type' , $batch_type)->update(['completed_at' => Carbon::now()]);
    }
}
