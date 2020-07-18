<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutstandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_outstandings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('purchasing_purchase_order_code', 20)->nullable();
            $table->char('purchasing_service_order_code', 20)->nullable();
            $table->char('supplier_code',20);
            $table->enum('payment_type', ['full', 'terms'])->default('full');
            $table->date('due_date')->nullable();
            $table->bigInteger('currency')->default(1);
            $table->float('exchange_rate', 12, 2)->nullable();
            $table->integer('terms_number')->nullable();
            $table->integer('terms_flat')->nullable();
            $table->integer('terms_range')->nullable();
            $table->integer('terms_unit')->default(2);
            $table->integer('ppn')->default(0);
            $table->float('ppn_percen', 8, 2)->default(10);
            $table->float('ppn_nominal', 12, 2)->default(0);
            $table->enum('ppn_status', ['include','exclude'])->default('include');
            $table->enum('discon', [0, 1])->default(0);
            $table->float('discon_percen', 8, 2)->default(0);
            $table->float('discon_nominal', 12, 2)->default(0);
            $table->longText('discon_reason')->nullable();
            $table->datetime('issue_date');
            $table->string('issued_by', 20);
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('purchasing_outstandings');
    }
}
