<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Stock\Entities\ItemUnitConversion;
use Modules\Stock\Entities\Item;
use Modules\General\Entities\Unit;

$factory->define(ItemUnitConversion::class, function (Faker $faker) {
    return [
        'unit_id' => Unit::all()->random()->id,
        'conversion_value' => '10',
        'is_primary' => 1,
    ];
});
