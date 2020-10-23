<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_name');
            $table->double('price');
            $table->string('customer_email');
            $table->integer('customer_mobile');
            $table->smallInteger('qantity')->nullable('false');
            $table->string('delivery')->nullable('false');
            $table->set('payment',['cash','visa'])->nullable('false');
            $table->integer('order_number');
            $table->date('order_time');
            $table->text('notes');
            $table->date('recieve_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
