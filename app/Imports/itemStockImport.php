<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Stock\Entities\StockOldApp;

class itemStockImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new StockOldApp([
            'item_old_code' =>  $row['kode_barang'],
            'moq' => $row['minimum'],
            'name' => $row['barang'],
            'size' => $row['ukuran'],
            'tipe' => $row['tipe'],
            'brand' => $row['merek'],
            'color' => $row['warna'],
            'qty_borrow' => $row['pinjaman'],
            'qty_stock' =>  $row['kuantiti'],
            'unit_name' =>  $row['satuan'],
        ]);
    }
}
