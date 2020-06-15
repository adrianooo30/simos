<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliver_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('order_transaction_id');
            $table->foreign('order_transaction_id')->references('id')->on('order_transactions');

            $table->string('receipt_no');
            $table->date('delivery_date');

            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');
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
        Schema::dropIfExists('deliver_transactions');
    }
}
