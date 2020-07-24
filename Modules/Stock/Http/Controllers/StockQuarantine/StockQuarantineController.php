<?php

namespace Modules\Stock\Http\Controllers\StockQuarantine;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\StockQuarantine;
use Modules\Stock\Entities\StockTransaction;

class StockQuarantineController extends Controller
{

    public function create()
    {
        return view('stock::create');
    }


    public function store(Request $request)
    {
        $items      = $request->items;
        $action     = $request->action;
        $approvals  = StockQuarantine::NOT_APPROVALS;
        $status     = ($action==1) ? StockQuarantine::RETURN_TO_STOCK : StockQuarantine::PENDING;
        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                $data = [
                    'item_code' => $item['item']['code'],
                    'stock_transaction_id' => $item['id'],
                    'quantity' => $item['stock_current'],
                    'item_unit_conversion_id' => $item['item_unit_conversion_id'],
                    'action' => $action,
                    'action_date' => date('Y-m-d H:i:s'),
                    'reason' => $item['reason'],
                    'issue_date' => date('Y-m-d H:i:s'),
                    'issued_by' => Auth::user()->employee_nik,
                    'approvals' => $approvals,
                    'status' => $status,
                ];
                $save = StockQuarantine::create($data);

                if ($action>0) {
                    $stock_category =  ($action==1) ? StockTransaction::SC_QUARANTINE : StockTransaction::SC_DEAD_STOCK;
                    StockTransaction::where('id', $item['id'])->update([
                        'stock_quarantine_id' => $save->id,
                        'stock_quarantine_date' => $save->action_date,
                        'stock_category' => $stock_category,
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'act' => 'New',
                'message' => __('Berhasil memproses barang karantina.'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function returnToStock(Request $request)
    {
        $items      = $request->items;
        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                $stock_quarantine_id = $item['stock_quarantine_id'];
                $data = [
                    'status' => StockQuarantine::RETURN_TO_STOCK,
                ];

                $save = StockQuarantine::where('id', $stock_quarantine_id)->update($data);

                StockTransaction::where('id', $item['stock_transaction_id'])->update([
                    'stock_quarantine_id' => $stock_quarantine_id,
                    'stock_quarantine_date' => date('Y-m-d'),
                    'stock_category' => StockTransaction::SC_QUARANTINE,
                ]);

            }
            DB::commit();
            return response()->json([
                'act' => 'New',
                'message' => __('Berhasil mengembalikan dead stock ke stok.'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
