<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Stock\Entities\Brand;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\ItemCategory;

$factory->define(Item::class, function (Faker $faker) {
    $item_name = $faker->name;
    $nickname = $faker->word;
    $type = $faker->word;
    $size = $faker->word;
    $color = $faker->colorName;
    $detail = $item_name."/".$nickname." ".$type." ".$size." ".$color;

    return [
        'item_category_id' => ItemCategory::all()->random()->id,
        'item_brand_id' => Brand::all()->random()->id,
        'code' => "BR".str_pad($faker->unique()->numberBetween(1, 9999), 4, 0, STR_PAD_LEFT),
        'name' => $item_name,
        'slug' => Str::slug($item_name, '-'),
        'nickname' => $nickname,
        'type' => $type,
        'size' => $size,
        'color' =>  $color,
        'detail' => $detail,
        'description' => $faker->realText($maxNbChars = 225, $indexSize = 2),
        'is_priority' => $faker->randomElement([0,1]),
        'borrowable' => $faker->randomElement([0,1]),
        'max_stock' => $faker->numberBetween(10,100),
        'min_stock' => $faker->numberBetween(1,10),
    ];
});


