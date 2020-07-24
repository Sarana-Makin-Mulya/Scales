<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('item_code',20);
            $table->float('quantity');
            $table->bigInteger('item_unit_conversion_id');
            $table->char('po_code',20)->nullable();
            $table->char('transaction_symbol',5);
            $table->char('transaction_name',225);
            $table->char('transaction_code',20);
            $table->dateTime('transaction_date');
            $table->bigInteger('ic_goods_request_item_id');
            $table->bigInteger('ic_goods_request_item_out_id');
            $table->bigInteger('ic_goods_request_item_return_id');
            $table->bigInteger('ic_goods_borrow_item_id');
            $table->bigInteger('stock_adjustment_item_id');
            $table->bigInteger('delivery_order_item_id');
            $table->char('ic_goods_return_id');
            $table->integer('entry_status')->default(0);
            $table->integer('status')->default(0);
            $table->integer('data_status')->default(0);
            $table->integer('stock_status')->default(0);
            $table->bigInteger('stock_closing_id');
            $table->longText('note');
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
        Schema::dropIfExists('stock_transactions');
    }
}
