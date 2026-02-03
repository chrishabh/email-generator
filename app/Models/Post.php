<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'category', 'image', 'slug', 'excerpt', 'content','author'
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        // Regenerate sitemap when post is created, updated, or deleted
        static::created(function () {
            Artisan::call('sitemap:generate');
        });
        
        static::updated(function () {
            Artisan::call('sitemap:generate');
        });
        
        static::deleted(function () {
            Artisan::call('sitemap:generate');
        });
    }
}
