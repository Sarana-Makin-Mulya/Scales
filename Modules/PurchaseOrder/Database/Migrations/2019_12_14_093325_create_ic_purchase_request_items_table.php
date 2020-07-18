<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcPurchaseRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('ic_purchase_request_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('ic_purchase_request_code',20);
            $table->char('item_code',20);
            $table->bigInteger('item_unit_conversion_id')->default(0);
            $table->float('quantity')->default(0);
            $table->mediumText('description')->nullable();
            $table->date('target_arrival_date')->nullable();
            $table->float('quantity_order')->default(0);
            $table->boolean('is_priority')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('purchase_type')->default(1);
            $table->integer('status')->default(1);
            $table->string('purchase_rejected_by', 20)->nullable();
            $table->dateTime('purchase_rejected_date')->nullable();
            $table->text('purchase_rejected_reason')->nullable();
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
        Schema::dropIfExists('ic_purchase_request_items');
    }
}
