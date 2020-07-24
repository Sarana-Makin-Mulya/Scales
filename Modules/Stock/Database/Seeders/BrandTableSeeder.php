<?php

namespace Modules\Stock\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\Brand;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();
        // factory(Brand::class, 25)->create();

        $brands = base_path('/Modules/Stock/Database/Seeders/sql/item_brands.sql');
        DB::statement(file_get_contents($brands));
    }
}
