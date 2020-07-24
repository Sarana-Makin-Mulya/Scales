<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Stock\Entities\ItemCategory;

$factory->define(ItemCategory::class, function (Faker $faker) {
    $category = $faker->name;
    return [
        'name' => $category,
        'slug' => Str::slug($category, '-'),
    ];
});
