<?php

namespace Modules\Stock\Http\Controllers\StockAdjustment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\StockAdjustment;
use Modules\Stock\Entities\StockAdjustmentItem;
use Modules\Stock\Entities\StockQuarantine;

class AjaxDestroyStockAdjustment extends Controller
{
    public function __invoke(Request $request, $code)
    {
        if ($stockAdjustment = StockAdjustment::find($code)) {

            DB::beginTransaction();
            try {
                $stockAdjustmentItem = StockAdjustmentItem::where('stock_adjustment_code', $code)->get();
                foreach ($stockAdjustmentItem as $sa) {
                    // Update Stock Quarantine / Dead Stock
                    if ($sa->stock_quarantine_id>0) {
                        StockQuarantine::where('id', $sa->stock_quarantine_id)
                        ->update([
                            'approvals' => StockQuarantine::NOT_APPROVALS,
                            'status' => StockQuarantine::PENDING,
                        ]);
                    }
                }

                $stockAdjustment->delete();
                $stockAdjustment->stockAdjustmentItem()->delete();

                DB::commit();
                return response()->json(['message' => 'Berhasil menghapus data penyesuaian stock dengan nomor "'. $stockAdjustment->code .'".']);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }

        }

        return response()->json(['message' => 'Tidak terdapat data dengan nomor '. $code], 404);
    }
}
