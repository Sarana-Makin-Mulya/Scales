<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use phpDocumentor\Reflection\Types\Nullable;

class CreateStockOpnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('stock_opname_group_id')->nullable();
            $table->enum('stockopname_type',['item','daily','period'])->default('item');
            $table->char('item_code',20);
            $table->bigInteger('item_unit_conversion_id');
            $table->float('quantity_prev')->default(0);
            $table->float('quantity_new')->nullable();
            $table->float('quantity_issue')->default(0);
            $table->float('quantity_adjustment')->default(0);
            $table->float('quantity_issue_approved')->default(0);
            $table->text('note')->nullable();
            $table->dateTime('issue_date');
            $table->string('issued_by',20);
            $table->integer('approvals_status')->default(0);
            $table->string('approvals_by',20)->nullable();
            $table->integer('status')->default(0);
            $table->integer('stock_status')->default(1);
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
        Schema::dropIfExists('stock_opnames');
    }
}
