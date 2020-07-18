<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingServiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_service_orders', function (Blueprint $table) {
            $table->char('code',20)->primary();
            $table->char('service_request_code',20);
            $table->char('supplier_code',20);
            $table->date('arrival_date');
            $table->string('pic',20);
            $table->text('description')->nullable();
            $table->bigInteger('currency')->nullable();
            $table->float('exchange_rate',12,2)->nullable();
            $table->integer('ppn')->default(0);
            $table->float('ppn_percen',12,2)->default(10);
            $table->float('ppn_nominal',12,2)->default(0);
            $table->integer('ppn_status')->default(0);
            $table->integer('payment_type')->default(1);
            $table->date('payment_due')->nullable();
            $table->integer('payment_terms')->nullable();
            $table->integer('payment_terms_flat')->nullable();
            $table->integer('payment_terms_range')->nullable();
            $table->enum('payment_terms_unit', ['days','week','month','year'])->default('month');
            $table->date('payment_terms_due')->nullable();
            $table->integer('payment_status')->default(1);
            $table->text('payment_note')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('issue_date');
            $table->string('issued_by',20);
            $table->float('report_progress_percen')->default(0);
            $table->integer('report_rate')->default(1);
            $table->integer('report_status')->default(1);
            $table->string('canceled_by', 20)->nullable();
            $table->dateTime('canceled_date')->nullable();
            $table->text('canceled_reason')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('status')->default(1);
            $table->integer('status_data')->default(0);
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
        Schema::dropIfExists('purchasing_service_orders');
    }
}
