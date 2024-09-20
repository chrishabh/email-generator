<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusColumnInSingleVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('bulk_upload_email_file_data', function (Blueprint $table) {
            $table->string('job_email_status')->nullable();
        });

        Schema::table('uploaded_and_download_file_names', function (Blueprint $table) {
            $table->string('job_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       

        Schema::table('bulk_upload_email_file_data', function (Blueprint $table) {
            $table->enum('job_email_status', ['valid', 'invalid'])->change();
        });

        Schema::table('uploaded_and_download_file_names', function (Blueprint $table) {
            $table->string('job_id')->dropIfExists();
        });
    }
}
