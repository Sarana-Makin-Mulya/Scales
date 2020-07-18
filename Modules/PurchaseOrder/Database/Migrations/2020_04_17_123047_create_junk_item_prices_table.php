<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJunkItemPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('junk_item_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('junk_item_buyer_code',20);
            $table->char('junk_item_code',20);
            $table->integer('type')->nullable(1);
            $table->bigInteger('currency_id')->nullable();
            $table->float('price',12,2)->nullable();
            $table->bigInteger('unit_id')->nullable();
            $table->mediumText('desc')->nullable();
            $table->string('approvals_type', 200)->nullable();
            $table->string('approvals_file', 255)->nullable();
            $table->char('approvals_by', 20)->nullable();
            $table->dateTime('approvals_date')->nullable();
            $table->integer('approvals_status')->default(1);
            $table->mediumText('approvals_note')->nullable();
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
        Schema::dropIfExists('junk_item_prices');
    }
}
