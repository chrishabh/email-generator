<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersTablePlanIpLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('plan_type')->nullable()->after('prefill_email');
            $table->string('plan_amount')->nullable()->after('prefill_email');
            $table->string('plan_currency')->nullable()->after('plan_amount');
            $table->string('payment_ip')->nullable()->after('plan_currency');
            $table->string('country_code')->nullable()->after('payment_ip');
            $table->string('country_currency')->nullable()->after('country_code');
            $table->string('promo_code')->nullable()->after('country_currency');
            $table->string('applied_gst_per')->nullable()->after('promo_code');
            $table->string('gst_amount')->nullable()->after('applied_gst_per');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
