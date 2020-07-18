<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_purchase_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('purchasing_purchase_order_code',20);
            $table->char('item_code',20);
            $table->string('item_detail',225);
            $table->bigInteger('item_unit_conversion_id')->default(0);
            $table->string('item_unit',100);
            $table->float('quantity')->default(0);
            $table->float('price',12,2)->default(0);
            $table->mediumText('description')->nullable();
            $table->date('due_date');
            $table->integer('is_active')->default(1);
            $table->integer('status')->default(1);
            $table->integer('status_quantity_order')->default(1);
            $table->float('total_quantity_order')->default(0);
            $table->float('total_quantity_return')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('purchasing_purchase_order_items');
    }
}
