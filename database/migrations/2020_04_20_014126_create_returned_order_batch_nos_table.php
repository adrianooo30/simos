<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnedOrderBatchNosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returned_order_batch_nos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('returned_order_medicine_id');
            $table->unsignedBigInteger('batch_no_id');

            $table->integer('quantity');
            $table->string('reason');

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
        Schema::dropIfExists('returned_order_batch_nos');
    }
}
