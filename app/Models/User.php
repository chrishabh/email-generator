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
           SELECT 
            u.id AS userId, 
            u.name, 
            u.email, 
            u.mobile_number, 
            u.work_experience_description, 
            u.gender, 
            uc.credits, 
            u.email_verified AS verified, 
            COALESCE(evl.used_credits, 0) AS used_credits
        FROM users u
        LEFT JOIN (
            SELECT user_id, COUNT(id) AS used_credits
            FROM email_verification_logs
            GROUP BY user_id
        ) evl ON u.id = evl.user_id
        LEFT JOIN (
            SELECT * 
            FROM user_credits 
            WHERE deleted_at IS NULL 
            ORDER BY id DESC
        ) AS uc 
            ON uc.user_id = u.id
        WHERE u.deleted_at IS NULL 
        AND u.role = 'user'
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

        $verified_count = "SELECT COUNT(id) as verified_user
            FROM users u
            WHERE u.deleted_at IS NULL AND u.role = 'user' AND u.email_verified = '1'";

        $totalVerifiedUsersResult = DB::select($verified_count);
        $totalVerifiedUsers = $totalVerifiedUsersResult[0]->verified_user;
    
        $result = DB::select($query, [$offset, $perPage]);
    
        return [
            'data' => $result,
            'total' => $totalUsers,  // Total users count
            'verified_user' => $totalVerifiedUsers,
            'perPage' => $perPage,
            'currentPage' => $currentPage
        ];
    }

    public static function getUnVerifiedUsers()
    {
        $return = User::whereNull('deleted_at')->where('email_verified', '0')->get();

        if(count($return)>0){
            return $return->toArray();
        }

        return [];
    }

    public static function getUsersListForReengament()
    {
        return User::join('user_credits', 'users.id', '=', 'user_credits.user_id')
            ->whereNull('users.deleted_at')
            ->whereNull('user_credits.deleted_at')
            ->where('user_credits.credits', '>', 50)
            ->select('users.id', 'users.email')
            ->get()
            ->toArray();
    }
    
}
