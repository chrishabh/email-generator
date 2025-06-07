<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained('integration_tools')->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status',['verified','pending'])->default('pending');
            $table->string('emails')->nullable();  
            $table->string('name')->nullable();
            $table->string('mc_token')->nullable();
            $table->string('mc_dc')->nullable();
            $table->string('mc_user_id')->nullable(); 
            $table->string('service_name'); 
            $table->json('metadata')->nullable(); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('integrations');
    }
}
