<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Stock\Entities\StockOpname as EntitiesStockOpname;

class StockOpnameImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $item_unit_conversion = getUnitConversionId($row['kode']);
        return new EntitiesStockOpname([
            'item_code' => $row['kode'],
            'quantity_prev' => $row['stok'],
            'quantity_new' => $row['stok_baru'],
            'item_unit_conversion_id_prev' => $item_unit_conversion['id'],
            'item_unit_conversion_id_new' => $item_unit_conversion['id'],
            'issued_by' => Auth::user()->employee_nik,
            'issue_date' => Carbon::now()
        ]);
    }
}
