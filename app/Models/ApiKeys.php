<?php

namespace App\Models;

use App\Enums\CustomApiEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ApiKeys extends Model
{
    use HasFactory,SoftDeletes; 

    protected $fillable = [
        'user_id', 
        'name', 
        'key',  
        'action',
        'status',
        'is_deleted' 
    ];

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format('jS M Y, g:i:s a');
    }

    protected static function boot()
    {
        parent::boot(); 
        static::deleting(function ($apiKey) {
            if (isset($apiKey->deleteReason)) {
                $apiKey->action  = $apiKey->deleteReason;
                unset($apiKey->deleteReason);
            }else{
                $apiKey->action     = CustomApiEnum::DELETED;
            }
            $apiKey->is_deleted = '1'; 
            $apiKey->save();
        });
    }

    public static function getDataWithPagination($perPage, $currentPage){
        $offset = ($currentPage - 1) * $perPage;
        $totalUsersQuery = "
        SELECT COUNT(*) as total
        FROM api_keys as u WHERE u.deleted_at IS NULL AND u.is_deleted='0' ";  
        $totalResult = DB::select($totalUsersQuery);
        $totalData = $totalResult[0]->total;

        $query = "
            SELECT * from api_keys as u
            WHERE u.deleted_at IS NULL AND u.is_deleted='0' 
            LIMIT ?, ?
        ";
        $result = DB::select($query, [$offset, $perPage]);
    
        return [
            'data'        => $result,
            'total'       => $totalData,
            'perPage'     => $perPage,
            'currentPage' => (int)$currentPage
        ];
    }
}
