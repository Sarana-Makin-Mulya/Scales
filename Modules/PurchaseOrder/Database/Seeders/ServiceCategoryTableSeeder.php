<?php

namespace Modules\PurchaseOrder\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\PurchaseOrder\Entities\ServiceCategory;

class ServiceCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Mesin',
                'type' => 'machine',
                'description' => '-',
            ],
            [
                'name' => 'Barang',
                'type' => 'item',
                'description' => '-',
            ],
            [
                'name' => 'Lain-lain',
                'type' => 'other',
                'description' => '-',
            ],
            [
                'name' => 'Konstruksi',
                'type' => null,
                'description' => '-',
            ],

        ];

        foreach ($categories as $category) {
        $GoodsReturnCategory = ServiceCategory::where('name', $category['name'])->first();
        if (empty($GoodsReturnCategory)) {
            ServiceCategory::create($category);
        }
        }
        //factory(ServiceCategory::class, 5)->create();
    }
}
