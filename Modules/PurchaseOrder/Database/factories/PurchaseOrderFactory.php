<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Employee\Entities\Employee;
use Modules\PurchaseOrder\Entities\PurchaseOrder;
use Modules\PurchaseOrder\Entities\PurchaseOrderItems;
use Modules\Supplier\Entities\Supplier;

$factory->define(PurchaseOrder::class, function (Faker $faker) {
    return [
        'code' => "POS".str_pad($faker->unique()->numberBetween(1, 9999), 4, 0, STR_PAD_LEFT),
        'po_type' => 1,
        'supplier_code' => Supplier::all()->random()->code,
        'due_date' => $faker->date($format = 'Y-m-d', $max = '2019-11-30'),
        'pic' => Employee::all()->random()->nik,
        'note'=> $faker->text(100),
        'issue_date'=> $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
        'issued_by' => Employee::all()->random()->nik,
        'status' => PurchaseOrder::PROCESS,
    ];
});

$factory->define(PurchaseOrderItems::class, function (Faker $faker) {
    return [
        'item_code' => '',
        'item_unit_conversion_id' => '',
        'price' => $faker->numberBetween($min = 10000, $max = 5000000),
        'quantity' => $faker->numberBetween($min = 1, $max = 5),
        'description' => $faker->text(50),

    ];
});
