<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_medicines', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('order_transaction_id');
            // $table->foreign('order_transaction_id')->references('id')->on('order_transactions');

            $table->unsignedBigInteger('product_id');
            // $table->foreign('product_id')->references('id')->on('products');

            $table->integer('free');
            $table->integer('unit_price');

            // $table->bigInteger('quantity');
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
        Schema::dropIfExists('order_medicines');
    }
}
