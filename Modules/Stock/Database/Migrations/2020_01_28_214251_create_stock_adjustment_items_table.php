<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAdjustmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustment_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('stock_adjustment_code',20);
            $table->char('item_code',20);
            $table->bigInteger('item_unit_conversion_id');
            $table->float('quantity');
            $table->bigInteger('stock_adjustment_category_id');
            $table->text('description')->nullable();
            $table->bigInteger('stock_opname_group_id')->nullable();
            $table->bigInteger('stock_opname_id')->nullable();
            $table->dateTime('issue_date');
            $table->string('issued_by',20);
            $table->integer('approvals_status')->nullable();
            $table->dateTime('approvals_date')->nullable();
            $table->string('approvals_by',20)->nullable();
            $table->text('approvals_note')->nullable();
            $table->dateTime('release_date')->nullable();
            $table->string('release_by',20)->nullable();
            $table->text('release_note')->nullable();
            $table->integer('status')->default(0);
            $table->integer('data_status')->default(0);
            $table->boolean('is_active')->default(true);
            // $table->bigInteger('stock_quarantine_id')->nullable();
            // $table->float('release_qty')->nullable();
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
        Schema::dropIfExists('stock_adjustment_items');
    }
}
