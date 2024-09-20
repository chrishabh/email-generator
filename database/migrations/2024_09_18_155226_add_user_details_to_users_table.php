<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserDetailsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) { 
            $table->string('gender')->nullable()->after('password');
            $table->string('mobile_number')->nullable()->after('email'); 
            $table->date('date_of_birth')->nullable()->after('mobile_number');
            $table->text('work_experience_description')->nullable()->after('mobile_number'); 
            $table->boolean('confirm_password')->default(false)->after('password'); 
            $table->string('profile_picture_location')->nullable()->after('work_experience_description');
            $table->string('profile_picture_name')->nullable()->after('profile_picture_location');
            $table->enum('is_password_update_from_profile_screen',['0','1'])->default('0')->after('confirm_password');
            $table->enum('email_verified',['0','1'])->default('0')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gender'); 
            $table->dropColumn('mobile_number');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('work_experience_description');
            $table->dropColumn('confirm_password');
            $table->dropColumn('profile_picture_location');
            $table->dropColumn('profile_picture_name');
            $table->dropColumn('is_password_update_from_profile_screen');
        });
    }
}
