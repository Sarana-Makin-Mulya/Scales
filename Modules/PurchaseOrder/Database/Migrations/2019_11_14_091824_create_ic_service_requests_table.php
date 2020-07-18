<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIcServiceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ic_service_requests', function (Blueprint $table) {
            $table->char('code',20)->primary();
            $table->dateTime('issue_date');
            $table->date('due_date');
            $table->integer('service_category_id')->default(0);
            $table->integer('machine_id')->nullable();
            $table->char('item_code',20)->nullable();
            $table->text('description')->nullable();
            $table->string('request_by',20);
            $table->string('issued_by',20);
            $table->string('canceled_by', 20)->nullable();
            $table->dateTime('canceled_date')->nullable();
            $table->text('canceled_reason')->nullable();
            $table->string('rejected_by', 20)->nullable();
            $table->dateTime('rejected_date')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->boolean('is_priority')->default(true);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('ic_service_requests');
    }
}
