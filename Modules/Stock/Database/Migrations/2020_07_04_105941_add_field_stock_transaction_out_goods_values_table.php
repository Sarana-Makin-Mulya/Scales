<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldStockTransactionOutGoodsValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_transaction_out_goods_values', function (Blueprint $table) {
            $table->integer('out_ic_goods_borrow_item_id')->nullable()->after('out_stock_adjustment_item_id');
            $table->char('out_goods_return_code', 20)->nullable()->after('out_ic_goods_borrow_item_id');
            $table->integer('borrow_status')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_transaction_out_goods_values', function (Blueprint $table) {
            $table->dropColumn(['out_ic_goods_borrow_item_id', 'out_goods_return_code', 'borrow_status']);
        });
    }
}
