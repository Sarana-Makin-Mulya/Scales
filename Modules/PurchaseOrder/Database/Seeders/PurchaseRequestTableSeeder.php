<?php

namespace Modules\PurchaseOrder\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\PurchaseOrder\Entities\PurchaseRequest;
use Modules\PurchaseOrder\Entities\PurchaseRequestItems;

class PurchaseRequestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        factory(PurchaseRequest::class, 5)->create()->each(function ($purchaseRequest) {
            $items = factory(PurchaseRequestItems::class, 3)->make();
            $purchaseRequest->purchaseRequestItems()->saveMany($items);
        });
    }
}
