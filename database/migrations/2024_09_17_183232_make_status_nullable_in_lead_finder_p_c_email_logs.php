<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeStatusNullableInLeadFinderPCEmailLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        DB::statement("ALTER TABLE lead_finder_p_c_email_logs DROP COLUMN status");
        Schema::table('lead_finder_p_c_email_logs', function (Blueprint $table) {
            $table->enum('status', ['valid', 'invalid', 'aborted'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
