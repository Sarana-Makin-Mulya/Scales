<?php

namespace Modules\Stock\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\ItemUnitConversion;

class ItemUnitConversionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();
        // factory(ItemUnitConversion::class, 10)->create();

        $unitconversions = base_path('/Modules/Stock/Database/Seeders/sql/item_unit_conversions.sql');
        DB::statement(file_get_contents($unitconversions));
    }
}
