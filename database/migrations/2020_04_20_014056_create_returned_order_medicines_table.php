<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnedOrderMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returned_order_medicines', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_transaction_id');
            $table->unsignedBigInteger('product_id');

            $table->string('status');

            $table->date('returned_date');

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
        Schema::dropIfExists('returned_order_medicines');
    }
}
