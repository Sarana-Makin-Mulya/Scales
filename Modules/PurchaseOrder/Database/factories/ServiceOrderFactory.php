<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Employee\Entities\Employee;
use Modules\PurchaseOrder\Entities\ServiceOrder;
use Modules\PurchaseOrder\Entities\ServiceOrderFee;
use Modules\Supplier\Entities\Supplier;

$factory->define(ServiceOrder::class, function (Faker $faker) {
    return [
        'code' => "POS".str_pad($faker->unique()->numberBetween(1, 9999), 4, 0, STR_PAD_LEFT),
        'supplier_code' => supplier::all()->random()->code,
        'arrival_date' => $faker->date($format = 'Y-m-d', $max = '2019-11-30'),
        'pic' => Employee::all()->random()->nik,
        'description' => $faker->realText($maxNbChars = 225, $indexSize = 2),
        'note' => $faker->realText($maxNbChars = 225, $indexSize = 2),
        'issue_date'=> $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
        'issued_by' => Employee::all()->random()->nik,
        'status' => ServiceOrder::PROCESS,
    ];
});

$factory->define(ServiceOrderFee::class, function (Faker $faker) {
    return [
        'service_name' => $faker->realText($maxNbChars = 50, $indexSize = 2),
        'price' => $faker->numberBetween($min = 10000, $max = 5000000),
        'quantity' => $faker->numberBetween($min = 1, $max = 5),
        'unit_id' => 1,
        'description' => $faker->realText($maxNbChars = 225, $indexSize = 2),

    ];
});
