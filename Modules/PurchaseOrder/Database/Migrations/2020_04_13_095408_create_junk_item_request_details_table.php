<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJunkItemRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('junk_item_request_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('junk_item_request_code');
            $table->char('junk_item_code');
            $table->char('junk_item_price_id');
            $table->float('price',12,2)->default(0);
            $table->float('quantity')->default(0);
            $table->integer('unit_id')->default(0);
            $table->text('description')->nullable();
            $table->dateTime('weighing_date')->nullable();
            $table->char('weighing_by', 20)->nullable();
            $table->text('weighing_desc')->nullable();
            $table->dateTime('report_date')->nullable();
            $table->char('report_by', 20)->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('junk_item_request_details');
    }
}
