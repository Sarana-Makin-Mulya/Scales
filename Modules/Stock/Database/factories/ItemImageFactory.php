<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Stock\Entities\ItemImage;
use Modules\Stock\Entities\Item;

$factory->define(ItemImage::class, function (Faker $faker) {
    return [
        'path' => $faker->imageUrl(150, 150, null, true, 'Barang'),
        'disk' => '-',
    ];
});
