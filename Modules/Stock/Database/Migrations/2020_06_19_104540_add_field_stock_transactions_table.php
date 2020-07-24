<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldStockTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->float('stock_out')->default(0)->after('quantity');
            $table->float('stock_current')->default(0)->after('stock_out');
            $table->bigInteger('stock_quarantine_id')->nullable()->after('ic_goods_return_id');
            $table->dateTime('stock_quarantine_date')->nullable()->after('stock_quarantine_id');
            $table->tinyInteger('storaged')->default(0)->after('stock_quarantine_date');
            $table->Integer('stock_category')->default(1)->after('stock_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn(['stock_out', 'stock_current', 'stock_quarantine_id', 'stock_quarantine_date', 'storaged', 'stock_category']);
        });
    }
}
