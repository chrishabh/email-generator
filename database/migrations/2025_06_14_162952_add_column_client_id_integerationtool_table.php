<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnClientIdIntegerationtoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('integration_tools', function (Blueprint $table) {
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable()->after('client_id');
            $table->json('url')->nullable()->after('client_secret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
