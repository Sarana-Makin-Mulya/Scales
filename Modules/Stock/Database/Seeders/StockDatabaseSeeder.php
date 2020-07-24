<?php

namespace Modules\Stock\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class StockDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(ItemCategoryTableSeeder::class);
        //$this->call(BrandTableSeeder::class);
        // $this->call(ItemTableSeeder::class);
        // $this->call(ItemUnitConversionTableSeeder::class);
        $this->call(StockAdjustmentCategoryTableSeeder::class);
        // $this->call(ItemImageTableSeeder::class);
    }
}
