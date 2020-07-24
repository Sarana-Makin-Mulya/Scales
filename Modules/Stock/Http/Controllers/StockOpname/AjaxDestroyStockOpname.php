<?php

namespace Modules\Stock\Http\Controllers\StockOpname;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\StockOpname;

class AjaxDestroyStockOpname extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ($stockOpname = StockOpname::find($id)) {

            DB::beginTransaction();
            try {
                $stockOpname->delete();
                DB::commit();
                return response()->json(['message' => 'Berhasil menghapus data stok opname "'. $stockOpname->item_code .'".']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }

        }

        return response()->json(['message' => 'Tidak terdapat data dengan id '. $id], 404);
    }
}
