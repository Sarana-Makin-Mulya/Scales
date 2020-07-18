<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutstandingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('purchasing_outstanding_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('outstanding_id');
            $table->char('purchasing_purchase_order_code', 20)->nullable();
            $table->char('purchasing_service_order_code', 20)->nullable();
            $table->integer('terms_number')->default(1);
            $table->float('nominal',12,2)->nullable();
            $table->float('percen',12,2)->nullable();
            $table->date('due_date')->nullable();
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
        Schema::dropIfExists('purchasing_outstanding_details');
    }
}
