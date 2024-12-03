<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignupLog extends Model
{
    use HasFactory;
    
    protected $table = 'signup_logs';
    protected $fillable = [
        'user_id',
        'status',
        'request_payload',
        'recaptcha_response',
        'ip_address',
    ];
}
