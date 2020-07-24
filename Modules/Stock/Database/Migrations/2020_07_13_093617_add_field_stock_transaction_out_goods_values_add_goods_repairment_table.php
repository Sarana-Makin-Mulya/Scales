<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldStockTransactionOutGoodsValuesAddGoodsRepairmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_transaction_out_goods_values', function (Blueprint $table) {
            $table->char('out_goods_repairment_code', 20)->nullable()->after('out_goods_return_code');
            $table->integer('repairment_status')->default(0)->after('borrow_status');
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
            $table->dropColumn(['out_goods_repairment_code', 'repairment_status']);
        });
    }
}
