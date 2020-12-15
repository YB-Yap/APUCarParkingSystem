<?php

/*
| Description: Used for creating tables in the database
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->enum('type', ['deduct', 'topup'])->default('deduct');
            $table->enum('category', ['parking', 'subscription'])->default('parking');
            $table->integer('amount')->unsigned();
            $table->enum('payment_method', ['apcard', 'admin'])->default('apcard');
            $table->string('description');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('transactions');
    }
}
