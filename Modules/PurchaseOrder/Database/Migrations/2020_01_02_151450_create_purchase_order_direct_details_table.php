<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderDirectDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_purchase_order_direct_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('purchasing_purchase_order_direct_id');
            $table->bigInteger('purchasing_purchase_order_item_id');
            $table->char('item_code',20);
            $table->float('price',12,2)->default(0);
            $table->float('quantity')->default(0);
            $table->bigInteger('item_unit_conversion_id')->default(0);
            $table->mediumText('description')->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('purchasing_purchase_order_direct_details');
    }
}
