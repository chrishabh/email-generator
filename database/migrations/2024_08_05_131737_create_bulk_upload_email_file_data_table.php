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
            $table->string('email')->nullable();
            $table->enum('isValidEmail', [0, 1])->default(0);
            $table->enum('type', ['single', 'bulk'])->nullable();
            $table->unsignedBigInteger('importedBy');
            $table->string('fileName')->nullable();
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
