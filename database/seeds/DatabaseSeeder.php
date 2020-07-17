<?php

use Illuminate\Database\Seeder;
use Modules\Company\Database\Seeders\ProductionCompanyTableSeeder;
use Modules\Company\Database\Seeders\ProductionPositionLevelTableSeeder;
use Modules\Company\Database\Seeders\ProductionPositionTableSeeder;
use Modules\Company\Database\Seeders\ProductionWorkingAreaTableSeeder;
use Modules\Regional\Database\Seeders\VillagesTableSeeder;
use Modules\Regional\Database\Seeders\DistricsTableSeeder;
use Modules\Regional\Database\Seeders\ProvincesTableSeeder;
use Modules\Regional\Database\Seeders\RegenciesTableSeeder;
use Modules\General\Database\Seeders\ProductionContactTypesTableSeeder;
use Modules\General\Database\Seeders\ProductionUnitTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // General Seeder
        $this->call(ProductionContactTypesTableSeeder::class);
        $this->call(ProductionUnitTableSeeder::class);

        // Region
        // $this->call(ProvincesTableSeeder::class);
        // $this->call(RegenciesTableSeeder::class);
        // $this->call(DistricsTableSeeder::class);
        // $this->call(VillagesTableSeeder::class);

        // Company
        $this->call(ProductionCompanyTableSeeder::class);
        // $this->call(ProductionPositionTableSeeder::class);
        // $this->call(ProductionPositionLevelTableSeeder::class);
        // $this->call(ProductionWorkingAreaTableSeeder::class);
    }
}
