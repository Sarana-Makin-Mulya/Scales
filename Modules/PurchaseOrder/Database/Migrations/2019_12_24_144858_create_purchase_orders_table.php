<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_purchase_orders', function (Blueprint $table) {
            $table->char('code',20)->primary();
            $table->integer('po_type')->default(1);
            $table->char('supplier_code',20)->nullable();
            $table->char('supplier_name',225)->nullable();
            $table->char('supplier_pic',225)->nullable();
            $table->char('supplier_address',225)->nullable();
            $table->char('supplier_phone',50)->nullable();
            $table->date('due_date');
            $table->string('pic',20);
            $table->string('pic_name',225);
            // $table->integer('ppn')->default(0);
            // $table->float('ppn_percen',12,2)->default(10);
            // $table->float('ppn_nominal',12,2)->default(0);
            // $table->integer('ppn_status')->default(0);
            // $table->bigInteger('currency')->default(1);
            // $table->float('exchange_rate',12,2)->nullable();
            // $table->integer('payment_type')->default(1);
            // $table->date('payment_due')->nullable();
            // $table->integer('payment_terms')->nullable();
            // $table->integer('payment_terms_flat')->nullable();
            // $table->integer('payment_terms_range')->nullable();
            // $table->enum('payment_terms_unit', ['days','week','month','year'])->default('month');
            // $table->date('payment_terms_due')->nullable();
            // $table->integer('payment_status')->default(1);
            // $table->text('payment_note')->nullable();
            $table->longText('note')->nullable();
            $table->datetime('issue_date');
            $table->string('issued_by',20);
            $table->integer('status')->default(1);
            $table->integer('status_data')->default(0);
            $table->integer('status_do')->default(1);
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
        Schema::dropIfExists('purchasing_purchase_orders');
    }
}
