<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Default');
            $table->string('sub_sku');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('size_id')->nullable();
            $table->foreign('size_id')->references('id')->on('sizes');
            $table->decimal('default_purchase_price', 15, 4);
            $table->decimal('default_sell_price', 15, 4);
            $table->boolean('is_dummy')->default(1);
            $table->unsignedBigInteger('pos_model_id')->nullable()->comment('Pos system model id');
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
        Schema::dropIfExists('variations');
    }
}
