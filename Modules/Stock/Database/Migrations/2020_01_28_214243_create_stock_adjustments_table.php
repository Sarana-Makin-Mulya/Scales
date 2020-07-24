<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAdjustmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->char('code',20)->primary();
            $table->dateTime('issue_date');
            $table->string('issued_by',20);
            $table->text('description')->nullable();
            $table->bigInteger('stock_opname_group_id')->nullable();
            $table->bigInteger('stock_opname_id')->nullable();
            $table->integer('status')->default(1);
            $table->integer('data_status')->default(0);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('stock_adjustments');
    }
}
