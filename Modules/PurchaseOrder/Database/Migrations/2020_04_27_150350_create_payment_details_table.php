<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_payment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('payment_code',20);
            $table->integer('termin')->default(1);
            $table->enum('po_type', ['PO', 'SO'])->default('PO');
            $table->char('purchasing_purchase_order_code',20)->nullable();
            $table->char('purchasing_service_order_code',20)->nullable();
            $table->bigInteger('outstanding_id')->nullable();
            $table->bigInteger('outstanding_detail_id')->nullable();
            $table->bigInteger('currency')->nullable();
            $table->float('exchange_rate',12,2)->nullable();
            $table->float('payment_nominal',12,2)->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('payment_img')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->string('issued_by',20)->nullable();
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('purchasing_payment_details');
    }
}
