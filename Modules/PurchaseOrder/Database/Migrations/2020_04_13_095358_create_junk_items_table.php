<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJunkItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('junk_items', function (Blueprint $table) {
            $table->char('code',20)->primary();
            $table->string('name',255);
            $table->mediumText('description')->nullable();
            $table->enum('type', ['income', 'cost'])->nullable('income');
            $table->bigInteger('unit_id')->nullable();
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
        Schema::dropIfExists('junk_items');
    }
}
