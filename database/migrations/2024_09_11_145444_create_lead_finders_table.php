<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLeadFindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_finders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Ensure it matches the type of 'id' in 'users' table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('firstName')->nullable(); 
            $table->string('lastName')->nullable(); 
            $table->string('domain')->nullable(); 
            $table->enum('isValidationPause',['0','1'])->default('0'); 
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lead_finders');
    }
}
