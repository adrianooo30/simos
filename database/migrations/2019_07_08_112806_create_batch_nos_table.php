<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchNosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_nos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');      
            // $table->foreign('product_id')->references('id')->on('products');

            $table->string('batch_no');
            $table->bigInteger('unit_cost');
            $table->bigInteger('quantity');
            $table->date('exp_date');
            $table->date('date_added');

            $table->unsignedBigInteger('supplier_id');
            // $table->foreign('supplier_id')->references('id')->on('suppliers');

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
        Schema::dropIfExists('batch_nos');
    }
}
