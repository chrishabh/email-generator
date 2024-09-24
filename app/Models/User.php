<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table ='users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'no_of_email_verification'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
 

    public static function getUserId($email)
    {
        return User::where('email', $email)->first()->id;
    }

    public static function getUserdata($email)
    {
        return User::where('email', $email)->first();
    }

    public static function getUserdataById($user_id)
    {
        return User::where('id', $user_id)->first();
    }

    public static function updatePassword($email,$password,$ip)
    {
        return User::where('email', $email)->update(['password'=> Hash::make($password),'password_updated_at' => Carbon::now(), 'password_update_ip'=>$ip ]);
    }


    static function insertDataAndgetId($array,$id=null){
        if(!empty($id))  $status = self::where('id',$id)->update($array);
        else
        $status = self::insert($array);
        return $status;
    }

    public static function verifyUser($user_id)
    {
        return User::whereNull('deleted_at')->where('id',$user_id)->update(['email_verified'=>'1','email_verified_at'=> Carbon::now()]);
    }


    static function getUserDetailsWithRemainingCredits($perPage, $currentPage,$isWorkEx=false){
        $offset = ($currentPage - 1) * $perPage;
    
        // Get the total number of users for pagination
        $totalUsersQuery = "
            SELECT COUNT(*) as total
            FROM users u
            LEFT JOIN (SELECT * FROM user_credits WHERE deleted_at IS NULL ORDER BY id DESC) as uc
            ON uc.user_id = u.id
            WHERE u.deleted_at IS NULL AND u.role = 'user'
        ";
        if($isWorkEx){
            $totalUsersQuery = "
            SELECT COUNT(*) as total
            FROM users u
            WHERE u.deleted_at IS NULL AND u.role = 'user' AND u.work_experience_description IS NOT NULL
        ";  
        }
        $totalUsersResult = DB::select($totalUsersQuery);
        $totalUsers = $totalUsersResult[0]->total;
    
        // Fetch paginated users with remaining credits
        $query = "
            SELECT u.id as userId, u.name, u.email, u.mobile_number, u.work_experience_description, u.gender, uc.credits
            FROM users u
            LEFT JOIN (SELECT * FROM user_credits WHERE deleted_at IS NULL ORDER BY id DESC) as uc
            ON uc.user_id = u.id
            WHERE u.deleted_at IS NULL AND u.role = 'user'
            LIMIT ?, ?
        ";

        if($isWorkEx){
            $query = "
            SELECT u.id as userId, u.name, u.email, u.mobile_number, u.work_experience_description, u.gender
            FROM users u
            WHERE u.deleted_at IS NULL AND u.role = 'user' AND u.work_experience_description IS NOT NULL
            LIMIT ?, ?
        ";  
        }
    
        $result = DB::select($query, [$offset, $perPage]);
    
        return [
            'data' => $result,
            'total' => $totalUsers,  // Total users count
            'perPage' => $perPage,
            'currentPage' => $currentPage
        ];
    }
    
}
