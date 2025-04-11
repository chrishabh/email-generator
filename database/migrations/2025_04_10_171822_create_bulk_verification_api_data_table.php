<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBulkVerificationApiDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_verification_api_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id'); 
            $table->foreign('file_id')->references('id')->on('bulk_verification_api_jobs')->onDelete('cascade');;
            $table->string('email')->nullable();
            $table->string('status',100)->nullable();
            $table->string('reason',1000)->nullable();
           
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
        Schema::dropIfExists('bulk_verification_api_data');
    }
}
