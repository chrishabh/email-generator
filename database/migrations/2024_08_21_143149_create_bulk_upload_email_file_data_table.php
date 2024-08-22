<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkUploadEmailFileDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_upload_email_file_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id'); 
            $table->foreign('file_id')->references('id')->on('uploaded_and_download_file_names')->onDelete('cascade');;
            $table->string('email')->nullable();
            $table->enum('isValidEmail', [0, 1])->default(0);
            $table->enum('type', ['single', 'bulk'])->nullable();
            $table->unsignedBigInteger('importedBy');
            $table->enum('status',['invalid','valid'])->nullable();
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
        Schema::dropIfExists('bulk_upload_email_file_data');
    }
}
