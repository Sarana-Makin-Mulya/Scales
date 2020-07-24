<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldStockAdjustmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_adjustment_items', function (Blueprint $table) {
            $table->bigInteger('stock_quarantine_id')->nullable()->after('description');
            $table->float('release_qty')->nullable()->after('approvals_note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_adjustment_items', function (Blueprint $table) {
            $table->dropColumn(['stock_quarantine_id' , 'release_qty']);
        });
    }
}
