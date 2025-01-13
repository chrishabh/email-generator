<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiKeyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_key_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_key_id');
            $table->string('key_name',20);
            $table->string('key');
            $table->enum('action', ['created', 'deleted','edited','regenerated'])->nullable();
            $table->timestamps();

            $table->foreign('api_key_id')->references('id')->on('api_keys')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_key_logs');
    }
}
