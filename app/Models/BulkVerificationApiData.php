<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkVerificationApiData extends Model
{
    use HasFactory;

    public static function createData($data)
    {
        return self::create([
            'file_id' => $data['file_id'],
            'email' => $data['email'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function getData($fileId)
    {
        return self::where('file_id', $fileId)->get();
    }

    public static function updateData($id,$fileId, $email, $status, $reason = null)
    {
        return self::where('file_id', $fileId)->where('id', $id)
            ->where('email', $email)
            ->update(['status' => $status, 'reason' => $reason]);
    }
   
}
