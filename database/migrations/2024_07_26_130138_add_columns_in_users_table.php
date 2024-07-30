<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_of_email_verification')->nullable()->after('password'); 
            $table->enum('role',['user','admin'])->default('user')->after('no_of_email_verification');
            $table->string('phone_number')->nullable()->after('role');
            $table->enum('is_email_send',['0','1'])->default('0')->after('phone_number');

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
            //
        });
    }
}
