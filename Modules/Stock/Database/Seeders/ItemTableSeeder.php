<?php

namespace Modules\Stock\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\ItemImage;
use Modules\Stock\Entities\ItemUnitConversion;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();
        // factory(Item::class, 25)->create()->each(function ($item) {
        //     $conversion = factory(ItemUnitConversion::class, 1)->make();
        //     $item->unitConversion()->saveMany($conversion);

        //     $images = factory(ItemImage::class, 2)->make();
        //     $item->images()->saveMany($images);
        // });

        $items = base_path('/Modules/Stock/Database/Seeders/sql/items.new.sql');
        DB::statement(file_get_contents($items));

    }
}
