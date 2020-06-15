<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderBatchNosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_batch_nos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('order_medicine_id');
            // $table->foreign('order_medicine_id')->references('id')->on('order_medicines');

            $table->unsignedBigInteger('batch_no_id');
            // $table->foreign('batch_no_id')->references('id')->on('batch_nos');

            $table->bigInteger('quantity');

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
        Schema::dropIfExists('order_batch_nos');
    }
}
