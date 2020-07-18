<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Employee\Entities\Employee;
use Modules\PurchaseOrder\Entities\PurchaseRequest;
use Modules\PurchaseOrder\Entities\PurchaseRequestItems;
use Modules\Stock\Entities\Item;

$factory->define(PurchaseRequest::class, function (Faker $faker) {
    return [
        'code' => "PR".str_pad($faker->unique()->numberBetween(1, 9999), 6, 0, STR_PAD_LEFT),
        'pic' => Employee::all()->random()->nik,
        'note'=> $faker->text(100),
        'issue_date' => '2019-12-12 12:12:12',
        'issued_by' => Employee::all()->random()->nik,
        'status' => PurchaseRequest::REQUEST,
    ];
});

$factory->define(PurchaseRequestItems::class, function (Faker $faker) {
    return [
        'item_code' => Item::all()->random()->code,
        'item_unit_conversion_id' => 1,
        'quantity' => $faker->numberBetween($min = 1, $max = 5),
        'description' => $faker->text(100),
        'due_date' =>  '2019-12-16',
        'is_priority' =>  1,
        'is_active' =>  1,

    ];
});
