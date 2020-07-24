<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockQuarantinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_quarantines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('item_code',20);
            $table->bigInteger('stock_transaction_id');
            $table->float('quantity');
            $table->bigInteger('item_unit_conversion_id');
            $table->integer('action')->default(0);
            $table->dateTime('action_date');
            $table->dateTime('issue_date');
            $table->string('issued_by',20);
            $table->text('reason')->nullable();
            $table->integer('status')->default(1); // untuk kembalikan ke stock status = 1, untuk dead stock = 0 ketika barang di keluarkan baru update menjadi status = 1
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
        Schema::dropIfExists('stock_quarantines');
    }
}
