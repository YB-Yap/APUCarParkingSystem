<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parkings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->enum('parking_zone', ['a1', 'a2', 'b'])->default('a2');
            $table->timestamp('time_in');
            $table->timestamp('time_out')->nullable();
            $table->double('duration', 5, 3)->nullable();
            $table->integer('fee')->nullable();        // once in for 0.25 hour charge fee
            $table->timestamps();
            $table->softDeletes();

            // adding foreign key constraint
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parkings');
    }
}