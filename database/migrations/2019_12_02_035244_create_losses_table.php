<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLossesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('losses', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('batch_no_id');
            $table->foreign('batch_no_id')->references('id')->on('batch_nos');

            $table->date('loss_date');

            $table->bigInteger('quantity');
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
        Schema::dropIfExists('losses');
    }
}
