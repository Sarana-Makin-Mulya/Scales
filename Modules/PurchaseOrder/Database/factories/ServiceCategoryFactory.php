<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\PurchaseOrder\Entities\ServiceCategory;

$factory->define(ServiceCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text(50)
    ];
});
