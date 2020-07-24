<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->char('code',20)->primary();
            $table->char('old_code',20)->nullable();
            $table->integer('item_category_id');
            $table->integer('item_brand_id')->nullable();
            $table->char('item_measure_id',20)->nullable();
            $table->string('name',225);
            $table->string('detail',255);
            $table->string('slug',225);
            $table->string('nickname',225)->nullable();
            $table->string('type',100)->nullable();
            $table->string('size',100)->nullable();
            $table->string('color',100)->nullable();
            $table->string('description',255)->nullable();
            $table->string('info',500)->nullable();
            $table->boolean('is_priority')->default(false);
            $table->boolean('borrowable')->default(false);
            $table->integer('max_stock')->default(0);
            $table->integer('min_stock')->default(0);
            $table->integer('current_stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('status_stock')->default(false);
            $table->integer('stock_app_old_id')->default(0);
            $table->char('create_by',20)->nullable();
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
        Schema::dropIfExists('items');
    }
}
