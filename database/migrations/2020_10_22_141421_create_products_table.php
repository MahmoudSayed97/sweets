<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description');
            $table->double('price')->nullable(false);
            $table->tinyInteger('status')->default(1);
            $table->string('image');
            $table->smallInteger('number_of_pieces');
            $table->enum('size',['small','medium','large']);
            $table->string('adding_something_special');
            $table->tinyInteger('enough_for');
            $table->string('delivery_time');
            $table->unsignedBigInteger('cat_id');

            $table->foreign('cat_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
