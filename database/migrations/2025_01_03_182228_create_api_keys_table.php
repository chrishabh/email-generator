<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Linking API key to a user
            $table->string('name');
            $table->string('key')->unique();
            $table->enum('status', ['Enabled', 'Disabled'])->default('Enabled');
            $table->enum('action', ['created', 'deleted','edited','regenerated'])->nullable();
            $table->enum('is_deleted',['0','1'])->default('0');
            $table->softDeletes(); 
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_keys');
    }
}
