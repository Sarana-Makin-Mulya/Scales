<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransactionOutGoodsValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transaction_out_goods_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('item_code',20);
            $table->bigInteger('stock_transaction_id')->nullable();
            $table->bigInteger('in_delivery_order_item_id')->nullable();
            $table->bigInteger('in_purchasing_purchase_order_item_id')->nullable();
            $table->bigInteger('in_stock_adjustment_item_id')->nullable();
            $table->char('transaction_symbol',5);
            $table->char('transaction_name',20);
            $table->char('transaction_code',20);
            $table->integer('transaction_category');
            $table->dateTime('transaction_date');
            $table->bigInteger('out_ic_goods_request_item_id')->nullable();
            $table->bigInteger('out_ic_goods_request_item_out_id')->nullable();
            $table->bigInteger('out_stock_adjustment_item_id')->nullable();
            $table->float('out_quantity');
            $table->bigInteger('out_item_unit_conversion_id');
            $table->float('cancel_quantity');
            $table->bigInteger('cancel_item_unit_conversion_id');
            $table->bigInteger('cancel_ic_goods_request_item_return_id')->nullable();
            $table->integer('status')->default(0);
            $table->integer('data_status')->default(0);
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
        Schema::dropIfExists('stock_transaction_out_goods_values');
    }
}
