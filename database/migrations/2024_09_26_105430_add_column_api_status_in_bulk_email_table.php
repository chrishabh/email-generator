<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnApiStatusInBulkEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bulk_upload_email_file_data', function (Blueprint $table) {
            $table->string('apiStatus')->nullable();
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
            $table->dropColumn('apiStatus'); 
        });
    }
}
