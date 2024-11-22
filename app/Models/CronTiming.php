<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronTiming extends Model
{
    use HasFactory;

    public static function getCronTiming($cron)
    {
        return CronTiming::whereNull('deleted_at')->where('cron_keyboard',$cron)->first();
    }
}
