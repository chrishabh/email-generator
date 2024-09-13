<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLeadFinderPCEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_finder_p_c_email_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_finder_id'); 
            $table->foreign('lead_finder_id')->references('id')->on('lead_finders')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->enum('status',['valid','invalid','aborted'])->default('invalid');
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
        Schema::dropIfExists('lead_finder_p_c_email_logs');
    }
}
