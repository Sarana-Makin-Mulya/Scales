<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Stock\Entities\Item as EntitiesItem;

class itemImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new EntitiesItem([
            'code' =>  $row['stock_code'],
            'item_category_id' => null,
            'item_brand_id' => null,
            'item_measure_id' =>  $row['measure_code'],
            'name' =>  $row['stock_name'],
            'slug' => null,
            'nickname' => null,
            'type' =>  $row['stock_type'],
            'size' =>  $row['stock_size'],
            'color' => $row['stock_color'],
            'detail' => null,
            'description' => $row['stock_description'],
            'info' => null,
            'is_priority' => null,
            'borrowable' => null,
            'max_stock' => $row['stock_min_qty'],
            'min_stock' => $row['stock_max_qty'],
            'current_stock' => null,
            'is_active' => null,
            'issued_by' => Auth::user()->employee_nik,
            'issue_date' => Carbon::now()
        ]);
    }
}
