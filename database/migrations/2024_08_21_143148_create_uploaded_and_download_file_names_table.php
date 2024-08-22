<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadedAndDownloadFileNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploaded_and_download_file_names', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_id'); // Ensure it matches the type of 'id' in 'users' table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('fileName')->nullable(); 
            $table->string('uploadedFileLocation')->nullable(); 
            $table->string('downloadFileName')->nullable();  
            $table->string('downloadFileLocation')->nullable(); 
            $table->enum('verificationStatus',['pending','verified'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploaded_and_download_file_names');
    }
}
