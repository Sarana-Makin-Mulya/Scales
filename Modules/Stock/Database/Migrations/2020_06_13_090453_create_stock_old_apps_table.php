<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockOldAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_old_apps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('item_old_code', 20)->nullable();
            $table->char('item_code', 20)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('size', 255)->nullable();
            $table->string('tipe', 255)->nullable();
            $table->string('brand', 255)->nullable();
            $table->string('color', 255)->nullable();
            $table->float('moq')->default(0);
            $table->float('qty_borrow')->default(0);
            $table->float('qty_stock')->default(0);
            $table->string('unit_name')->nullable();
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
        Schema::dropIfExists('stock_old_apps');
    }
}
