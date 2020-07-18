<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOutstandingDicsonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_outstanding_dicsons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('outstanding_id');
            $table->char('purchase_order_code', 20)->nullable();
            $table->bigInteger('purchase_order_item_id')->nullable();
            $table->char('service_order_code', 20)->nullable();
            $table->bigInteger('service_order_fee_id')->nullable();
            $table->float('discon_percen', 8, 2)->nullable();
            $table->float('discon_nominal', 12, 2)->nullable();
            $table->longText('note')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->char('issued_by', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('status_data')->default(false);
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
        Schema::dropIfExists('purchasing_outstanding_dicsons');
    }
}
