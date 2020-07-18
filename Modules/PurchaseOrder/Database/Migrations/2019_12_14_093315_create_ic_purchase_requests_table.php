<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcPurchaseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('ic_purchase_requests', function (Blueprint $table) {
            $table->char('code',20)->primary();
            $table->string('pic',20);
            $table->longText('note')->nullable();
            $table->dateTime('issue_date');
            $table->string('issued_by',20);
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
        Schema::dropIfExists('ic_purchase_requests');
    }
}
