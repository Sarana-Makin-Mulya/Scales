<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockOpnameGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_opname_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('file_name',255)->nullable();
            $table->bigInteger('total_item');
            $table->dateTime('issue_date');
            $table->string('issued_by',20);
            $table->enum('type',['daily','period']);
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('stock_opname_groups');
    }
}
