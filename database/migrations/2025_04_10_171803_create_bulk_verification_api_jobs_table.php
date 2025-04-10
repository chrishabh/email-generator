<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkVerificationApiJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_verification_api_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_id',50)->unique();
            $table->unsignedBigInteger('user_id',20);
            $table->string('api_key',50);
            $table->string('file_path',500);
            $table->string('file_name',500);
            $table->string('download_file_path',500)->nullable();
            $table->string('download_file_name',500)->nullable();
            $table->enum('status', ['new','preparing', 'verifying','completed','deleted','failed','cancelled']);

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
        Schema::dropIfExists('bulk_verification_api_jobs');
    }
}
