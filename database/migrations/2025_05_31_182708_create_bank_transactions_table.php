<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            // Date (Value Date)
            $table->string('value_date');

            // Particulars
            $table->longText('particulars');

            // Ref No./Cheque No
            $table->string('reference_no')->nullable();

            // Transaction Type
            $table->string('transaction_type');

            // Debit(Rs)
            $table->decimal('debit', 15, 2)->nullable();

            // Credit(Rs)
            $table->decimal('credit', 15, 2)->nullable();

            // Balance(Rs)
            $table->decimal('balance', 15, 2);

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
        Schema::dropIfExists('bank_transactions');
    }
}
