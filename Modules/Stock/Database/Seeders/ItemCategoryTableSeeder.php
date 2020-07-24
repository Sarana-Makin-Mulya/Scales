<?php

namespace Modules\Stock\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\ItemCategory;

class ItemCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    //     Model::unguard();

    //     $categories = [
    //         [
    //             'code' => 'ATK',
    //             'name' => 'Alat Tulis Kantor',
    //             'slug' => 'alat-tulis-kantor'
    //         ],
    //         [
    //             'code' => 'BGN',
    //             'name' => 'Bangunan',
    //             'slug' => 'bangunan'
    //         ],
    //         [
    //             'code' => 'BBR',
    //             'name' => 'Batubara',
    //             'slug' => 'barybara'
    //         ],
    //         [
    //             'code' => 'KDR',
    //             'name' => "Kendaraan",
    //             'slug' => 'kendaraan'
    //         ],
    //         [
    //             'code' => 'LAB',
    //             'name' => "Laboratorium",
    //             'slug' => 'laboratorium'
    //         ],
    //         [
    //             'code' => 'LSK',
    //             'name' => "Listrik",
    //             'slug' => 'listrik'
    //         ],
    //         [
    //             'code' => 'MKK',
    //             'name' => "Mekanik",
    //             'slug' => 'mekanik'
    //         ],
    //         [
    //             'code' => 'UMM',
    //             'name' => "Umum",
    //             'slug' => 'umum'
    //         ]
    //    ];

    //    foreach ($categories as $category) {
    //     ItemCategory::create($category);
    //    }
        //factory(ItemCategory::class, 10)->create();

        $categories = base_path('/Modules/Stock/Database/Seeders/sql/item_categories.sql');
        DB::statement(file_get_contents($categories));
    }
}
