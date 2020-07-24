<?php

namespace Modules\Stock\Http\Controllers\StockClosing;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\StockClosing;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Entities\StockTransactionCategory;

class StockClosingController extends Controller
{

    public function index()
    {
        return view('stock::closing.index');
    }

    public function store(Request $request)
    {
        $arr_check      = [];
        $arr_max        = [];
        $closing        = $request->input('closingStock');
        $closing_status = $closing['status'];
        $closing_month  = $closing['month'];
        $closing_month_name  = $closing['monthName'];
        $closing_year   = $closing['year'];
        $issue_date     = str_pad($closing_year,4,0,STR_PAD_LEFT)."-".str_pad($closing_month,2,0,STR_PAD_LEFT)."-01";

        $checkClosing = StockClosing::query()
            ->whereYear('issue_date', '=', $closing_year)
            ->whereMonth('issue_date', '=', $closing_month)
            ->where('status',StockClosing::ACTIVE)
            ->first();

        $itemTransaction = getItemTransaction($closing_month, $closing_year);

        if (!empty($checkClosing) or $closing_status==1) {
            return response()->json([
                'status' => 'already',
                'message' => __('Anda sudah melakukan stock closing bulan '.$closing_month_name.' - '.$closing_year.' pada '.$checkClosing->created_at),
                ]);
        } else {
            $request->validate([
                'closingStock' => 'required',
            ]);

            $data = [
                'issue_date' => $issue_date,
                'issued_by' => Auth::user()->employee_nik,
                'note' => $request->input('note'),
                'status' => StockClosing::ACTIVE,
            ];

            DB::beginTransaction();
            try {
                $stockClosing = new StockClosing();
                $save = $stockClosing->create($data);

                // Set New Stock
                if (count($itemTransaction)>0) {
                    $dataItem = Item::query()
                        ->whereIn('code', $itemTransaction)
                        ->orderBy('current_stock', 'Desc')
                        ->get();

                    foreach ($dataItem as $item) {
                        $conversion = getUnitConversionId($item->code);
                        $checkStockFirst = StockTransaction::query()
                            ->where('item_code', $item->code)
                            ->where('transaction_code', "CS".str_pad($closing_month,2,0,STR_PAD_LEFT).$closing_year)
                            ->where('status', StockTransaction::STATUS_DONE)
                            ->where('entry_status', StockTransaction::STOCK_SUMMARY)
                            ->first();

                        if (empty($checkStockFirst)) {
                            $stock = getItemStockClosing($item->code, $closing_month, $closing_year);
                            $arr_check[] = $item->code." create : ".$closing_year." - ".$closing_month." : ".$stock;
                            StockTransaction::create([
                                    'item_code' => $item->code,
                                    'quantity'=> $stock,
                                    'item_unit_conversion_id' => $conversion['id'],
                                    'transaction_symbol'=> "CS",
                                    'transaction_name' => "Closing Stock",
                                    'transaction_code' => "CS".str_pad($closing_month,2,0,STR_PAD_LEFT).$closing_year,
                                    'transaction_date' => $issue_date,
                                    'entry_status' => StockTransaction::STOCK_SUMMARY,
                                    'data_status' => StockTransaction::DATA_OPEN,
                                    'status' => StockTransaction::STATUS_DONE,
                                    'stock_closing_id' => $save->id,
                                    'note' => 'Stok Awal'
                                ]);
                        } else {
                            $arr_check[] = $item->code." ready : ".$closing_year." - ".$closing_month;
                        }


                        // Update Item
                        $qty_out = getTotalItemOut($item->code, $closing_month, $closing_year);
                        if ($item->max_stock < $qty_out) {
                            $arr_max[] = $item->code." create : ".$closing_year." - ".$closing_month." -> ".$item->max_stock." => ".$qty_out;
                            $min_stock = ceil($qty_out/3);
                            $max_stock = $qty_out;
                            Item::where('code', $item->code)
                                ->update(['min_stock' => $min_stock,
                                          'max_stock' => $max_stock,
                                          ]);
                        } else {
                            $arr_max[] = $item->code." minus : ".$closing_year." - ".$closing_month." -> ".$item->max_stock." => ".$qty_out;
                        }
                    }
                }

                // Clossing Transaction
                $stockTransaction = StockTransaction::query()
                    ->whereYear('created_at', '=', $closing_year)
                    ->whereMonth('created_at', '=', $closing_month)
                    ->where('entry_status','<>', StockTransaction::STOCK_SUMMARY)
                    ->where('status',StockTransaction::STATUS_DONE)
                    ->get();

                    foreach ($stockTransaction as $transaction) {
                        StockTransaction::where('id', $transaction->id)
                            ->update([
                                'stock_closing_id' => $save->id,
                                'data_status' => StockTransaction::DATA_LOCK
                            ]);

                        // Update transaksi status menjadi data lock
                        //    1. permintaan barang
                                // 'ic_goods_request_item_id',
                                // 'ic_goods_request_item_out_id',
                                // 'ic_goods_request_item_return_id',
                        //    2. Peminjaman Barang
                                // 'ic_goods_borrow_item_id',
                        //    3. penyesuaian stok
                                // 'stock_adjustment_item_id',
                        //    4. penerimaan barang
                                // 'delivery_order_item_id',
                        //    5. Retur Barang
                                // 'ic_goods_return_id',
                        //    6. Karantina
                                // 'stock_quarantine_id',
                    }

                DB::commit();
                return response()->json([
                    'itemTransaction' => $itemTransaction,
                    'check' => $arr_check,
                    'max' => $arr_max,
                    'message' => __('Berhasil melakukan stock closing bulan '.$closing_month_name.'-'.$closing_year),
                    ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
    }

    public function cancel(Request $request, $id)
    {
        if ($stockClosing=StockClosing::find($id)) {
            $issue_date     = $stockClosing->issue_date;
            $issue_month    = substr($issue_date, 5, 2);
            $issue_year     = substr($issue_date, 0, 4);
            DB::beginTransaction();
            try {
                StockTransaction::where('entry_status','<>', StockTransaction::STOCK_SUMMARY)
                    ->where('stock_closing_id', $stockClosing->id)
                    ->update([
                        'stock_closing_id' => 0,
                        'data_status' => StockTransaction::DATA_OPEN,
                    ]);

                StockTransaction::where('entry_status', StockTransaction::STOCK_SUMMARY)
                    ->where('stock_closing_id', $stockClosing->id)
                    ->update([
                        'data_status' => StockTransaction::DATA_OPEN,
                        'status' => StockTransaction::STATUS_CANCEL,
                    ]);

                StockClosing::where('id', $stockClosing->id)->update(['status' => StockClosing::CANCEL]);
                DB::commit();
                return response()->json(['message' => __('Berhasil membatalkan stock closing bulan '.$issue_month.'-'.$issue_year)]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }
}
