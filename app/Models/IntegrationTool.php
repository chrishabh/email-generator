<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrationTool extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'icon','slug'];
    protected $appends = ['icon_url'];
    
    public function integrations()
    {
        return $this->hasMany(Integration::class, 'tool_id');
    }

    public function getIconUrlAttribute(){
        return asset('/integration/integerated-icon/'.$this->icon);
    }
}
