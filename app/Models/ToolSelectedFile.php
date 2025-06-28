<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolSelectedFile extends Model
{
    use HasFactory;

    protected $table = 'tool_selected_files';
    protected $fillable = [
        'tool_id',
        'file_name',
        'file_path',
        'user_id',
        'tool_name',
    ];

    public function integrationTool()
    {
        return $this->belongsTo(IntegrationTool::class, 'tool_id');
    }
}
