<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('translations')->nullable();
            $table->string('description')->nullable();
            $table->integer('sort')->default(1);
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('pos_model_id')->nullable()->comment('Pos system product class id');;
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
        Schema::dropIfExists('product_classes');
    }
}
