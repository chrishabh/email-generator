<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class create_user_support extends Model
{
    use HasFactory;

    public static function addSupport($userId, $supportEmail, $textContent)
    {
        $support = new self();
        $support->user_id = $userId;
        $support->support_email = $supportEmail;
        $support->text_content = $textContent;
        $support->save();

        return $support;
    }
}
