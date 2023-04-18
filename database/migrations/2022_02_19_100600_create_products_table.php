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
            $table->id();
            $table->string('name');
            $table->text('translations')->nullable();
            $table->unsignedBigInteger('product_class_id')->nullable();
            $table->foreign('product_class_id')->references('id')->on('product_classes')->onDelete('cascade');
            $table->string('sku');
            $table->string('multiple_units')->nullable();
            $table->boolean('is_service')->default(1);
            $table->text('product_details')->nullable();
            $table->decimal('purchase_price', 15, 4);
            $table->decimal('sell_price', 15, 4);
            $table->string('discount_type')->nullable();
            $table->decimal('discount', 15, 4)->nullable();
            $table->string('discount_start_date')->nullable();
            $table->string('discount_end_date')->nullable();
            $table->enum('type', ['single', 'variable'])->default('single');
            $table->boolean('active')->default(1);
            $table->unsignedBigInteger('edited_by')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('pos_model_id')->nullable()->comment('Pos system product id');
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
        Schema::dropIfExists('products');
    }
}
