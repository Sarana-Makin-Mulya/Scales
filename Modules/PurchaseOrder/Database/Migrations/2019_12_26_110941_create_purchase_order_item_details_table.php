<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_purchase_order_item_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('purchasing_purchase_order_item_id');
            $table->char('ic_purchase_request_code', 20);
            $table->char('item_code', 20);
            $table->bigInteger('ic_purchase_request_item_id');
            $table->float('quantity_req')->default(0);
            $table->float('quantity_order')->default(0);
            $table->float('quantity_po')->default(0);
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('purchasing_purchase_order_item_details');
    }
}
