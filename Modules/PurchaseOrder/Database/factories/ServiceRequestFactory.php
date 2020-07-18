<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Modules\Employee\Entities\Employee;
use Modules\General\Entities\Machine;
use Modules\PurchaseOrder\Entities\ServiceCategory;
use Modules\PurchaseOrder\Entities\ServiceRequest;

$factory->define(ServiceRequest::class, function (Faker $faker) {
    return [
        'code' => "RS".str_pad($faker->unique()->numberBetween(1, 9999), 4, 0, STR_PAD_LEFT),
        'issue_date' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now'),
        'due_date' => $faker->date($format = 'Y-m-d', $max = '2019-11-30'),
        'service_category_id' => ServiceCategory::all()->random()->id,
        'machine_id' => Machine::all()->random()->id,
        'description' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'is_priority' => $faker->boolean(),
        'is_active' => '1',
        'request_by' => Employee::all()->random()->nik,
        'issued_by' => Employee::all()->random()->nik,
        'status' => ServiceRequest::PROCESS,
    ];
});
