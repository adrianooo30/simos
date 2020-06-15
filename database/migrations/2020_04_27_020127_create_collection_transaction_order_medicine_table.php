<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionTransactionOrderMedicineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_transaction_order_medicine', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('order_medicine_id');
            // $table->foreign('order_transaction_id')->references('id')->on('order_transactions');

            $table->unsignedBigInteger('collection_transaction_id');
            // $table->foreign('collection_transaction_id')->references('id')->on('collection_transactions');

            $table->integer('paid_quantity');
            $table->integer('paid_amount');

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
        Schema::dropIfExists('collection_transaction_order_medicine');
    }
}
