<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Modules\Stock\Entities\Brand;

$factory->define(Brand::class, function (Faker $faker) {
    $brand = $faker->company;
    return [
        'name' => $brand,
        'slug' => Str::slug($brand),
        'is_active' => $faker->boolean(),
    ];
});
