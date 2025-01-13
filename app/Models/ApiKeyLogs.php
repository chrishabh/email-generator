<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKeyLogs extends Model
{
    use HasFactory;
    protected $fillable = [
        'api_key_id', 
        'action',  
        'key_name',
        'key',
        'action'  
    ];
}
