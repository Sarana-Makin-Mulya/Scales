<?php

namespace Modules\PurchaseOrder\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\PurchaseOrder\Entities\ServiceOrder;
use Modules\PurchaseOrder\Entities\ServiceOrderFee;
use Modules\PurchaseOrder\Entities\ServiceRequest;

class ServiceRequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //factory(ServiceRequest::class, 25)->create();

        factory(ServiceRequest::class, 5)->create()->each(function ($ServiceRequest) {
            $ServiceOrder = factory(ServiceOrder::class, 1)->create()->each(function ($so) {
                $of = factory(ServiceOrderFee::class, 3)->make();
                $so->ServiceOrderFee()->saveMany($of);
            });
            $ServiceRequest->serviceOrder()->saveMany($ServiceOrder);
        });
    }
}
