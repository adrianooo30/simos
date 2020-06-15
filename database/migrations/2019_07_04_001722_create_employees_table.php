<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // image
            $table->string('profile_img')->nullable();

            // identity
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');

            // position
            // $table->unsignedBigInteger('position_id');
            // $table->foreign('position_id')->references('id')->on('positions');

            // where to find
            $table->string('contact_no');
            $table->longText('address');

            // system accessors
            $table->string('username');
            
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // timestamps
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
        Schema::dropIfExists('user_employees');
    }
}
