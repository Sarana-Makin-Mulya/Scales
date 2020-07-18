<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_payments', function (Blueprint $table) {
            $table->char('code',20)->primary();
            $table->char('supplier_code',20);
            $table->bigInteger('currency')->default(1);
            $table->float('exchange_rate',12,2)->nullable();
            $table->float('nominal',12,2)->nullable();
            $table->float('supplier_saldo',12,2)->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->integer('payment_method')->default(1);
            $table->integer('company_bank_account')->nullable();
            $table->integer('supplier_bank_account')->nullable();
            $table->string('payment_img')->nullable();
            $table->longText('payment_note')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->string('issued_by',20);
            $table->integer('is_active')->default(1);
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('purchasing_payments');
    }
}
