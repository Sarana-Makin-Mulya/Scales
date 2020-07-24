<?php

namespace Modules\Stock\Http\Controllers\StockOpname;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\StockOpname;
use Modules\Stock\Entities\StockOpnameGroup;
use Modules\Stock\Transformers\StockOpname\ItemStockOpnameDailyResource;

class AjaxGetItemStockOpnameDailyExcel extends Controller
{
    public function __invoke(Request $request)
    {
        $date_now = now();
        $group = StockOpnameGroup::query()
            ->where('type', 'daily')
            ->whereDate('issue_date', $date_now)
            ->where('status', '<>', StockOpnameGroup::CANCELED)
            ->first();

        if (!empty($group)) {
            $stockOpname = StockOpname::query()
                ->where('stock_opname_group_id', $group->id)
                ->get();
        } else {
            // save group
            DB::beginTransaction();
            try {
                $stockOpnameGroup = StockOpnameGroup::create([
                    'file_name' => "-",
                    'type' => 'daily',
                    'issued_by' => Auth::user()->employee_nik,
                    'issue_date' => $date_now,
                ]);

                // save stockopname item
                $item = Item::all()->random(10);
                foreach ($item as $row) {
                    $item_code = $row['code'];
                    $stock     = getItemStock($item_code);
                    $item_unit_conversion = getUnitConversionId($item_code);

                    $dataStockOpname = [
                        'stock_opname_group_id'=> $stockOpnameGroup->id,
                        'stockopname_type' => 'daily',
                        'item_unit_conversion_id' => $item_unit_conversion['id'],
                        'item_code' => $item_code,
                        'quantity_prev' => $stock,
                        'quantity_new' => null,
                        'quantity_issue' => 0,
                        'issue_date' => Carbon::now(),
                        'issued_by' => Auth::user()->employee_nik,
                        'status' => StockOpname::ENTRY,
                        'stock_status' => StockOpname::STOCK_WAITING,
                    ];

                    StockOpname::create($dataStockOpname);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
            // kirim hasil
            $stockOpname = StockOpname::query()
                ->where('stock_opname_group_id', $stockOpnameGroup->id)
                ->get();
        }

        return ItemStockOpnameDailyResource::collection($stockOpname);
    }
}
