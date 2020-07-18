<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingServiceOrderReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_service_order_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('service_order_code',20);
            $table->char('service_request_code',20);
            $table->date('report_date');
            $table->string('report_by',20);
            $table->text('description')->nullable();
            $table->float('progress_percen')->nullable();
            $table->integer('rate')->nullable();
            $table->integer('status')->default(1);// progress, completed
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
        Schema::dropIfExists('purchasing_service_order_reports');
    }
}
