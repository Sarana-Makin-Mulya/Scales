<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrderDirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_purchase_order_directs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('purchasing_purchase_order_code',20);
            $table->char('purchasing_purchase_order_code_suffix',20);
            $table->char('supplier_code',20)->nullable();
            $table->date('purchase_date');
            $table->longText('note')->nullable();
            $table->datetime('issue_date');
            $table->string('issued_by',20);
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
        Schema::dropIfExists('purchasing_purchase_order_directs');
    }
}
