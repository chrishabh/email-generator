<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignupLogsTable extends Migration
{
    public function up()
    {
        Schema::create('signup_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Nullable for failed signups
            $table->string('status')->default('failed'); // 'failed' or 'successful'
            $table->json('request_payload'); // Store full request as JSON
            $table->text('recaptcha_response')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('signup_logs');
    }
}
