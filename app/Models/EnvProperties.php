<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvProperties extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public function scopeEnabled($query)
    {
        return $query->where("disabled_YN","=","N");
    }
}
