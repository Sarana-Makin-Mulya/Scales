<?php

namespace Modules\PurchaseOrder\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(ServiceCategoryTableSeeder::class);
        //$this->call(ServiceRequestTableSeeder::class);
        //$this->call(PurchaseRequestTableSeeder::class);

    }
}
