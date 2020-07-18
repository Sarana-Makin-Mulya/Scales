<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingPurchaseOrderTerminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_purchase_order_termins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('purchasing_purchase_order_code',20);
            $table->float('nominal',12,2)->nullable();
            $table->float('percen')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('pay_status')->default(1);
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
        Schema::dropIfExists('purchasing_purchase_order_termins');
    }
}
