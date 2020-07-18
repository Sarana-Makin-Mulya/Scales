<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJunkItemRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('junk_item_requests', function (Blueprint $table) {
            $table->char('code',20)->primary();
            $table->char('buyer_code',20)->nullable();
            $table->string('buyer_name',225)->nullable();
            $table->string('buyer_pic',225)->nullable();
            $table->string('buyer_address',225)->nullable();
            $table->string('buyer_phone',50)->nullable();
            $table->dateTime('buyer_arrivals_date');
            $table->string('smm_pic',20);
            $table->string('smm_pic_name',225);
            $table->bigInteger('currency')->default(1);
            $table->float('exchange_rate',12,2)->nullable();
            $table->date('payment_due')->nullable();
            $table->integer('payment_status')->default(1);
            $table->longText('note')->nullable();
            $table->datetime('issue_date');
            $table->char('issued_by',20);
            $table->integer('status')->default(1);
            $table->integer('status_data')->default(0);
            $table->integer('status_weighing')->default(1);
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
        Schema::dropIfExists('junk_item_requests');
    }
}
