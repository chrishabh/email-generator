<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Integration extends Model
{
    use HasFactory;
    use SoftDeletes;
    // protected $fillable = ['tool_id', 'status', 'emails'];
    
    public function tool()
    {
        return $this->belongsTo(IntegrationTool::class, 'tool_id');
    }
}
