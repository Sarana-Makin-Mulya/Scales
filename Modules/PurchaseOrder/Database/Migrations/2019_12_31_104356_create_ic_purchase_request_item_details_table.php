<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcPurchaseRequestItemDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ic_purchase_request_item_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ic_purchase_request_item_id');
            $table->bigInteger('ic_goods_request_item_id')->nullable();
            $table->char('item_code',20);
            $table->float('quantity_req')->default(0);
            $table->bigInteger('item_unit_conversion_id')->nullable();
            $table->date('target_arrival_date')->nullable();
            $table->integer('priority')->default(0);
            $table->longText('description')->nullable();
            $table->string('source', 255);
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('ic_purchase_request_item_details');
    }
}
