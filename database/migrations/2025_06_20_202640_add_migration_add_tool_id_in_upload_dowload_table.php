<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMigrationAddToolIdInUploadDowloadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('uploaded_and_download_file_names', function (Blueprint $table) {
            $table->unsignedBigInteger('tool_id')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uploaded_and_download_file_names', function (Blueprint $table) {
            //
        });
    }
}
