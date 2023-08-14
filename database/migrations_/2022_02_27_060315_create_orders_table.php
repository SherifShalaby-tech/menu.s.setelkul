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
            $table->id();
            $table->text('sales_note')->nullable();
            $table->unsignedBigInteger('store_id');
            $table->string('customer_name');
            $table->string('phone_number');
            $table->text('address')->nullable();
            $table->string('order_type');
            $table->integer('month')->nullable();
            $table->integer('day')->nullable();
            $table->integer('year')->nullable();
            $table->string('time')->nullable();
            $table->string('delivery_type');
            $table->string('payment_type');
            $table->integer('table_no')->nullable();
            $table->decimal('final_total', 15, 4);
            $table->decimal('discount_amount', 15, 4)->default(0);
            $table->string('status');
            $table->string('delivery_status')->nullable();
            $table->string('ip')->nullable();
            $table->unsignedBigInteger('pos_transaction_id')->nullable()->comment('Pos system Transaction Id');

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
        Schema::dropIfExists('orders');
    }
}
