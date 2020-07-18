<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingServiceOrderFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_service_order_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('service_order_code',20);
            $table->string('service_name',255);
            $table->float('price',12,2)->default(0);
            $table->float('quantity')->default(0);
            $table->bigInteger('unit_id')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('purchasing_service_order_fees');
    }
}
