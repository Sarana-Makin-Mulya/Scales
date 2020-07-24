<?php

namespace Modules\Stock\Http\Controllers\TransactionCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockTransactionCategory;

class AjaxDestroyStockTransactionCategory extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($trasactioncategory = StockTransactionCategory::find($id)) {
            $trasactioncategory->delete();
            return response()->json(['message' => 'Berhasil menghapus data transaksi kategori dengan nama "'. $trasactioncategory->name .'".']);
        }
    }
}
