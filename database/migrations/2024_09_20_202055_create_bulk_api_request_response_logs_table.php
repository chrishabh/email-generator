<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBulkApiRequestResponseLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_api_request_response_logs', function (Blueprint $table) {
            $table->id();
            $table->string('job_id')->nullable();
            $table->unsignedBigInteger('file_id');
            $table->string('file_name')->nullable();
            $table->string('which_api')->nullable();
            $table->string('url')->nullable();
            $table->string('request')->nullable();
            $table->text('response')->nullable(); 
            $table->string('api_status_code')->nullable(); 
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
        Schema::dropIfExists('bulk_api_request_response_logs');
    }
}
