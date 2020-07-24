<?php

namespace Modules\Stock\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Stock\Entities\StockAdjustmentCategory;
use Modules\Stock\Entities\StockTransactionOutGoodsValue;

class StockAdjustmentCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $categories = [
            [
                'name' => 'Rusak',
                'stock' => StockAdjustmentCategory::STOCK_OUT,
                'type_value' =>  StockTransactionOutGoodsValue::GOODS_VALUE,
            ],
            [
                'name' => 'Kadaluarsa',
                'stock' => StockAdjustmentCategory::STOCK_OUT,
                'type_value' =>  StockTransactionOutGoodsValue::GOODS_VALUE,
            ],
            [
                'name' => 'Hilang',
                'stock' => StockAdjustmentCategory::STOCK_OUT,
                'type_value' =>  StockTransactionOutGoodsValue::PENALTY_VALUE,
            ],
            [
                'name' => 'Penambahan Stok',
                'stock' => StockAdjustmentCategory::STOCK_IN,
                'type_value' =>  StockTransactionOutGoodsValue::GOODS_VALUE,
            ],
            [
                'name' => 'Dead Stock',
                'stock' => StockAdjustmentCategory::STOCK_OUT,
                'type_value' =>  StockTransactionOutGoodsValue::GOODS_VALUE,
            ],

        ];

        foreach ($categories as $category) {
            $StockAdjustmentCategory = StockAdjustmentCategory::where('name',$category['name'])->first();
            if(empty($StockAdjustmentCategory)){
                StockAdjustmentCategory::create($category);
            }
        }
    }
}
