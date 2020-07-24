<?php

namespace Modules\Stock\Http\Controllers\StockAdjustment;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\StockAdjustment;
use Modules\Stock\Entities\StockAdjustmentItem;
use Modules\Stock\Entities\StockOpname;
use Modules\Stock\Entities\StockQuarantine;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Entities\StockTransactionCategory;
use Modules\Stock\Entities\StockTransactionOutGoodsValue;
use PDF;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('stock::adjustment.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'items.*.item_code.code' => 'required',
            'items.*.item_unit_conversion_id.id' => 'required',
            'items.*.quantity' => 'required',
            'items.*.stock_adjustment_category_id.id' => 'required',
        ]);

        $data = [
            'code' => generateCodeAdjustment(),
            'type' => StockAdjustment::NORMAL,
            'issue_date' => Carbon::now(),
            'issued_by' => Auth::user()->employee_nik,
            'description' => $request->input('description'),
            'status' => StockAdjustment::REQUEST,
        ];

        DB::beginTransaction();
        try {
            $stockAdjustment = new StockAdjustment();
            $save = $stockAdjustment->create($data);
            $items = $request->items;
            if (!empty($items)) {
                foreach ($items as $item) {
                    $data = [
                        'stock_adjustment_code' => $save->code,
                        'item_code' => $item['item_code']['code'],
                        'item_unit_conversion_id' => $item['item_unit_conversion_id']['id'],
                        'quantity' => $item['quantity'],
                        'stock_adjustment_category_id' => $item['stock_adjustment_category_id']['id'],
                        'description' => $item['description'],
                        'issued_by' => Auth::user()->employee_nik,
                        'issue_date' => now(),
                        'is_active' => 1,
                        'approvals_status' => StockAdjustmentItem::APPROVALS_PENDING,
                        'status' => StockAdjustmentItem::REQUEST,
                        'data_status' => StockAdjustmentItem::DATA_OPEN,
                    ];

                    StockAdjustmentItem::create($data);
                }
            }

            // Notification : OK
            // 4 : Direksi
            createNotification([
                'notification_group_id' => 4,
                'type' => 'notification',
                'transaction_type' => 'sa',
                'transaction_code' => $save->code,
                'transaction_id' => null,
                'from' => 'gudang-umum',
                'to' => 'direksi',
                'message' => 'Penyesuaian Stok : '.$save->code,
                'url' => 'po/service-request',
                'status' => 'new',
                'issued_by' => Auth::user()->employee_nik,
            ]);

            DB::commit();
            return response()->json([
                'code' => $save->code,
                'changed' => true,
                'act' => 'New',
                'message' => __('Berhasil menambahkan data penyesuaian stok.'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function storeDeadStock(Request $request)
    {
        $data = [
            'code' => generateCodeAdjustment(),
            'type' => StockAdjustment::DEADSTOCK,
            'issue_date' => Carbon::now(),
            'issued_by' => Auth::user()->employee_nik,
            'description' => 'Pengeluaran barang dead stock',
            'status' => StockAdjustment::REQUEST,
        ];

        DB::beginTransaction();
        try {
            $stockAdjustment = new StockAdjustment();
            $save = $stockAdjustment->create($data);
            $items = $request->items;
            if (!empty($items)) {
                foreach ($items as $item) {
                    $data = [
                        'stock_adjustment_code' => $save->code,
                        'stock_quarantine_id' => $item['stock_quarantine_id'],
                        'item_code' => $item['item_code'],
                        'item_unit_conversion_id' => $item['item_unit_conversion_id'],
                        'quantity' => $item['quantity'],
                        'stock_adjustment_category_id' => $item['stock_adjustment_category_id'],
                        'description' => $item['reason'],
                        'issued_by' => Auth::user()->employee_nik,
                        'issue_date' => now(),
                        'is_active' => 1,
                        'approvals_status' => StockAdjustmentItem::APPROVALS_PENDING,
                        'status' => StockAdjustmentItem::REQUEST,
                        'data_status' => StockAdjustmentItem::DATA_OPEN,
                    ];
                    StockAdjustmentItem::create($data);

                    StockQuarantine::where('id', $item['stock_quarantine_id'])
                        ->update([
                            'approvals' => StockQuarantine::WAITING_APPROVALS,
                            'status' => StockQuarantine::APPROVALS,
                        ]);
                }
            }

            // Notification : OK
            // 4 : Direksi
            createNotification([
                'notification_group_id' => 4,
                'type' => 'notification',
                'transaction_type' => 'sa',
                'transaction_code' => $save->code,
                'transaction_id' => null,
                'from' => 'gudang-umum',
                'to' => 'direksi',
                'message' => 'Penyesuaian Stok : '.$save->code,
                'url' => 'po/service-request',
                'status' => 'new',
                'issued_by' => Auth::user()->employee_nik,
            ]);

            DB::commit();
            return response()->json([
                'code' => $save->code,
                'changed' => true,
                'act' => 'New',
                'message' => __('Berhasil mengajukan pengeluaran barang dead stok.'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $code)
    {
        if ($stockAdjustment = StockAdjustment::find($code)) {
            $request->validate([
                'items.*.item_code.code' => 'required',
                'items.*.item_unit_conversion_id.id' => 'required',
                'items.*.quantity' => 'required',
                'items.*.stock_adjustment_category_id.id' => 'required',
            ]);

            $data = [
                'issue_date' => Carbon::now(),
                'issued_by' => Auth::user()->employee_nik,
                'description' => $request->input('description'),
            ];

            DB::beginTransaction();
            try {
                $stockAdjustment->update($data);
                StockAdjustmentItem::where('stock_adjustment_code', $code)->update(['is_active' => 0]);
                $items = $request->items;
                    if (!empty($items)) {
                        foreach ($items as $item) {
                            $checkStockAdjustmentItem = StockAdjustmentItem::query()
                                ->where('stock_adjustment_code',$code)
                                ->where('item_code',$item['item_code']['code'])
                                ->where('item_unit_conversion_id',$item['item_unit_conversion_id']['id'])
                                ->where('quantity',$item['quantity'])
                                ->where('stock_adjustment_category_id',$item['stock_adjustment_category_id']['id'])
                                ->where('description',$item['description'])
                                ->where('is_active',0)
                                ->first();

                            $stockAdjustmentItemId = empty($item['id']) ? empty($checkStockAdjustmentItem) ? "" : $checkStockAdjustmentItem->id : $item['id'];

                            $data = [
                                'stock_adjustment_code' => $code,
                                'item_code' => $item['item_code']['code'],
                                'item_unit_conversion_id' => $item['item_unit_conversion_id']['id'],
                                'quantity' => $item['quantity'],
                                'stock_adjustment_category_id' => $item['stock_adjustment_category_id']['id'],
                                'description' => $item['description'],
                                'is_active' => 1,
                            ];

                            if (empty($stockAdjustmentItemId)) {
                                $stockAdjustment->stockAdjustmentItem()->create($data);
                            } else {
                                $stockAdjustment->stockAdjustmentItem()->where('id', $stockAdjustmentItemId)->update($data);
                            }
                        }
                    }

                    // Update Quarantine if is_active = 0
                    $checkNonActive = StockAdjustmentItem::where('stock_adjustment_code', $code)->where('is_active', 0)->get();
                    foreach ($checkNonActive as $nonActive) {
                        // Update Stock Quarantine / Dead Stock
                        if ($nonActive->stock_quarantine_id>0) {
                            StockQuarantine::where('id', $nonActive->stock_quarantine_id)
                            ->update([
                                'approvals' => StockQuarantine::NOT_APPROVALS,
                                'status' => StockQuarantine::PENDING,
                            ]);
                        }
                    }

                    // Update Quarantine if is_active = 0
                    StockAdjustmentItem::where('stock_adjustment_code', $code)
                        ->where('is_active', 0)
                        ->delete();

                    DB::commit();
                    return response()->json([
                        'code' => $code,
                        'changed' => changeDetection($stockAdjustment),
                        'act' => 'Update',
                        'message' => __('Berhasil mengubah data penyesuaian stok.'),
                    ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function approvals()
    {
        return view('stock::stock.adjustment_approvals_main');
    }

    public function approvalsPerItem()
    {
        return view('stock::stock.adjustment_approvals_per_item');
    }

    public function approvalsUpdate(Request $request, $code)
    {
        if ($stockAdjustment = StockAdjustment::find($code)) {
            DB::beginTransaction();
            try {
                $items = $request->items;
                foreach ($items as $item) {
                    $item_code                   = $item['item_code'];
                    $quantity                    = $item['quantity'];
                    $item_unit_conversion_id     = $item['item_unit_conversion_id']['id'];
                    $stock_adjustment_item_id    = $item['id'];
                    $approvals_status            = $item['approvals_status'];
                    $approvals_note              = $item['approvals_note'];
                    $stock_adjustment_status     = getStockAdjustmentCategoryStock($item['stock_adjustment_category_id']['id']);
                    $stock_adjustment_type_value = getStockAdjustmentCategoryTypeValue($item['stock_adjustment_category_id']['id']);
                    $transaction_category        = ($stock_adjustment_type_value==2) ? StockTransactionOutGoodsValue::PENALTY_VALUE : StockTransactionOutGoodsValue::GOODS_VALUE;
                    $entry_status                = ($stock_adjustment_status==1) ? StockTransaction::STOCK_IN : StockTransaction::STOCK_OUT;
                    $stock_status                = ($stock_adjustment_status==1) ? StockTransaction::STOCK_AVAILABLE : StockTransaction::STOCK_EMPTY;


                    // Update stock untuk penyesuaian stock dengan type= 2 (Stock Opname)
                    if ($stockAdjustment->type==2) {
                        // B : Stock Transaction
                        if ($approvals_status==StockAdjustmentItem::APPROVALS_APPROVED) {
                            // APPROVED

                            // Save perhitungan biaya barang
                            if ($entry_status==StockTransaction::STOCK_OUT) {
                                $data_stock = [
                                    'item_code' => $item_code,
                                    'quantity' => $quantity,
                                    'item_unit_conversion_id' => $item_unit_conversion_id,
                                    'transaction_symbol' => "SA",
                                    'transaction_name' => "Penyesuaian Stok",
                                    'transaction_code' => $code,
                                    'transaction_date' => date('Y-m-d H:i:s'),
                                    'stock_adjustment_item_id' => $stock_adjustment_item_id,
                                    'entry_status' => $entry_status,
                                    'status' => StockTransaction::STATUS_DONE,
                                    'stock_status' => $stock_status,
                                ];

                                $setData  = [
                                    'item_code' => $item_code,
                                    'transaction_symbol' => "SA",
                                    'transaction_name' => "Penyesuaian Stok",
                                    'transaction_code' => $code,
                                    'transaction_date' => date('Y-m-d H:i:s'),
                                    'transaction_category' => $transaction_category,
                                    'out_quantity' => $quantity,
                                    'out_item_unit_conversion_id' => $item_unit_conversion_id,
                                    'out_ic_goods_request_item_id' => 0,
                                    'out_ic_goods_request_item_out_id' => 0,
                                    'out_stock_adjustment_item_id' => $stock_adjustment_item_id,
                                    'out_ic_goods_borrow_item_id' => 0,
                                    'out_goods_return_code' => 0,
                                    'out_goods_repairment_code' => 0,
                                    'borrow_status' => StockTransactionOutGoodsValue::BORROW_NOT,
                                    'repairment_status' => StockTransactionOutGoodsValue::REPAIRMENT_NOT,

                                ];
                                createOutStockGoodsValue($quantity, $item_unit_conversion_id, $setData);
                            } else {
                                $data_stock = [
                                    'item_code' => $item_code,
                                    'quantity' => $quantity,
                                    'stock_out' => 0,
                                    'stock_current' => $quantity,
                                    'item_unit_conversion_id' => $item_unit_conversion_id,
                                    'transaction_symbol' => "SA",
                                    'transaction_name' => "Penyesuaian Stok",
                                    'transaction_code' => $code,
                                    'transaction_date' => date('Y-m-d H:i:s'),
                                    'stock_adjustment_item_id' => $stock_adjustment_item_id,
                                    'entry_status' => $entry_status,
                                    'status' => StockTransaction::STATUS_DONE,
                                    'stock_status' => $stock_status,
                                ];
                            }

                            $checkStockTransaction = StockTransaction::query()
                                ->where('stock_adjustment_item_id', $stock_adjustment_item_id)
                                ->where('entry_status', $entry_status)
                                ->first();

                            if (!empty($checkStockTransaction)) {
                                StockTransaction::where('id', $checkStockTransaction->id)->update($data_stock);
                            } else {
                                StockTransaction::create($data_stock);
                            }
                        } else {
                            // REJECTED
                            $checkStockTransaction = StockTransaction::query()
                                ->where('stock_adjustment_item_id', $stock_adjustment_item_id)
                                ->where('entry_status', $entry_status)
                                ->first();

                            if (!empty($checkStockTransaction)) {
                                // B : Update Stock Transaction
                                StockTransaction::where('id', $checkStockTransaction->id)->update(['status' => StockTransaction::STATUS_CANCEL]);

                                // B : Update Stock Transaction Goods Value
                                StockTransactionOutGoodsValue::where('out_stock_adjustment_item_id', $stock_adjustment_item_id)->update(['status' => StockTransactionOutGoodsValue::CANCEL]);

                                // B : Update Stock Transaction Goods Value
                                $stockTransaction = StockTransactionOutGoodsValue::where('out_stock_adjustment_item_id', $stock_adjustment_item_id)->get();
                                foreach ($stockTransaction as $transaction) {
                                    StockTransaction::where('id', $transaction->stock_transaction_id)->update([
                                        'stock_status' => StockTransaction::STOCK_AVAILABLE,
                                        'stock_out' => getStockTransaction($transaction->stock_transaction_id, 'out'),
                                        'stock_current' => getStockTransaction($transaction->stock_transaction_id, 'current'),
                                    ]);
                                }
                            }
                        }

                        // B : Update Stock Item
                        Item::where('code', $item_code)->update(['current_stock' => getItemStock($item_code)]);
                        $status =  StockAdjustmentItem::DONE;
                    } else {
                        $status =  StockAdjustmentItem::PROCESS;
                    }

                    // B : Update Adjustment Item
                    $data = [
                        'approvals_status' => $approvals_status,
                        'approvals_note' => $approvals_note,
                        'approvals_date' => Carbon::now(),
                        'approvals_by' => Auth::user()->employee_nik,
                        'status' => $status,
                    ];
                    StockAdjustmentItem::where('id', $stock_adjustment_item_id)->update($data);
                }

                // B : Update Adjustment
                $approvalsPending  = $this->checkRowItem($code, '=', StockAdjustmentItem::APPROVALS_PENDING);
                $approvalsProcess  = $this->checkRowItem($code, '<>', StockAdjustmentItem::APPROVALS_PENDING);
                $approvalsApproved = $this->checkRowItem($code, '=', StockAdjustmentItem::APPROVALS_APPROVED);
                $approvalsRejected = $this->checkRowItem($code, '=', StockAdjustmentItem::APPROVALS_REJECTED);
                $approvalsAll      = $this->checkRowItem($code, '=', 'all');

                if ($approvalsPending>0 and $approvalsProcess>0) {
                    $status = StockAdjustment::PROCESS_APPROVALS;
                } elseif ($approvalsPending>0 and $approvalsProcess<=0) {
                    $status = StockAdjustment::REQUEST;
                } else {
                    if ($stockAdjustment->type==2) {
                        $status = StockAdjustment::DONE;
                    } else {
                        if ($approvalsApproved>0) {
                            $status = StockAdjustment::WAITING_REPORT;
                        } else {
                            if ($approvalsRejected==$approvalsAll) {
                                $status = StockAdjustment::REJECTED;
                            } else {
                                $status = StockAdjustment::DONE;
                            }
                        }
                    }
                }

                $stockAdjustment->update(['status' => $status]);

                // Update status Quarantine/Dead Stock
                $checkStockAdjustmentItem = StockAdjustmentItem::query()
                    ->where('id', $stock_adjustment_item_id)
                    ->first();
                if (!empty($checkStockAdjustmentItem)) {
                    $stock_quarantine_id = $checkStockAdjustmentItem['stock_quarantine_id'];
                    $stock_opname_id     = $checkStockAdjustmentItem['stock_opname_id'];

                    if ($stock_quarantine_id>0) {
                        $approvals = ($approvals_status==StockAdjustmentItem::APPROVALS_APPROVED) ? StockQuarantine::APPROVED : StockQuarantine::REJECTED;
                        StockQuarantine::where('id', $stock_quarantine_id)
                            ->update([
                                'approvals' => $approvals,
                                'status' => StockQuarantine::APPROVALS,
                            ]);
                    } elseif ($stock_opname_id>0) {
                        $approvals = ($approvals_status==StockAdjustmentItem::APPROVALS_APPROVED) ? StockOpname::APPROVALS_APPROVED : StockOpname::APPROVALS_REJCTED;
                        StockOpname::where('id', $stock_opname_id)
                            ->update([
                                'approvals_status' => $approvals,
                                'approvals_by' => Auth::user()->employee_nik,
                            ]);
                    }
                }

                DB::commit();
                return response()->json(['message' => __('Berhasil menyimpan data persetujuan.')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function approvalsPerItemUpdate(Request $request, $id)
    {

        if ($stockAdjustmentItem = StockAdjustmentItem::find($id)) {
            $stock_adjustment_code   = $request->input('stock_adjustment_code');
            $stockAdjustmentType     = getStockAdjustmentType($stock_adjustment_code);
            $approvals_status        = $request->input('approvals_status');
            $approvals_note          = $request->input('approvals_note');
            $stock_adjustment_status = getStockAdjustmentCategoryStock($stockAdjustmentItem->stock_adjustment_category_id);
            $entry_status            = ($stock_adjustment_status==1) ? StockTransaction::STOCK_IN : StockTransaction::STOCK_OUT;
            $stock_status            = ($stock_adjustment_status==1) ? StockTransaction::STOCK_AVAILABLE : StockTransaction::STOCK_EMPTY;
            $item_code               = $stockAdjustmentItem->item_code;
            $stockAdjustmentItem_id  = $stockAdjustmentItem->id;
            $stock_quarantine_id     = $stockAdjustmentItem->stock_quarantine_id;
            $stock_opname_id         = $stockAdjustmentItem->stock_opname_id;
            $stock_adjustment_type_value = getStockAdjustmentCategoryTypeValue($stockAdjustmentItem->stock_adjustment_category_id);
            $transaction_category        = ($stock_adjustment_type_value==2) ? StockTransactionOutGoodsValue::PENALTY_VALUE : StockTransactionOutGoodsValue::GOODS_VALUE;

            DB::beginTransaction();
            try {

                // Update stock untuk penyesuaian stock dengan type= 2 (Stock Opname)
                if ($stockAdjustmentType==2) {
                    // B : Stock Transaction
                    if ($approvals_status==StockAdjustmentItem::APPROVALS_APPROVED) {


                        // Save perhitungan biaya barang
                        if ($entry_status==StockTransaction::STOCK_OUT) {
                            $data_stock = [
                                'item_code' => $item_code,
                                'quantity' => $stockAdjustmentItem->quantity,
                                'item_unit_conversion_id' => $stockAdjustmentItem->item_unit_conversion_id,
                                'transaction_symbol' => "SA",
                                'transaction_name' => "Penyesuaian Stok",
                                'transaction_code' => $stockAdjustmentItem->stock_adjustment_code,
                                'transaction_date' => date('Y-m-d H:i:s'),
                                'stock_adjustment_item_id' => $stockAdjustmentItem_id,
                                'entry_status' => $entry_status,
                                'status' => StockTransaction::STATUS_DONE,
                                'stock_status' => $stock_status,
                            ];

                            $setData  = [
                                'item_code' => $item_code,
                                'transaction_symbol' => "SA",
                                'transaction_name' => "Penyesuaian Stok",
                                'transaction_code' => $stockAdjustmentItem->stock_adjustment_code,
                                'transaction_date' => date('Y-m-d H:i:s'),
                                'transaction_category' => $transaction_category,
                                'out_quantity' => $stockAdjustmentItem->quantity,
                                'out_item_unit_conversion_id' => $stockAdjustmentItem->item_unit_conversion_id,
                                'out_ic_goods_request_item_id' => 0,
                                'out_ic_goods_request_item_out_id' => 0,
                                'out_stock_adjustment_item_id' => $stockAdjustmentItem_id,
                                'out_ic_goods_borrow_item_id' => 0,
                                'out_goods_return_code' => 0,
                                'out_goods_repairment_code' => 0,
                                'borrow_status' => StockTransactionOutGoodsValue::BORROW_NOT,
                                'repairment_status' => StockTransactionOutGoodsValue::REPAIRMENT_NOT,

                            ];
                            createOutStockGoodsValue($stockAdjustmentItem->quantity, $stockAdjustmentItem->item_unit_conversion_id, $setData);
                        } else {
                            $data_stock = [
                                'item_code' => $item_code,
                                'quantity' => $stockAdjustmentItem->quantity,
                                'stock_out' => 0,
                                'stock_current' => $stockAdjustmentItem->quantity,
                                'item_unit_conversion_id' => $stockAdjustmentItem->item_unit_conversion_id,
                                'transaction_symbol' => "SA",
                                'transaction_name' => "Penyesuaian Stok",
                                'transaction_code' => $stockAdjustmentItem->stock_adjustment_code,
                                'transaction_date' => date('Y-m-d H:i:s'),
                                'stock_adjustment_item_id' => $stockAdjustmentItem_id,
                                'entry_status' => $entry_status,
                                'status' => StockTransaction::STATUS_DONE,
                                'stock_status' => $stock_status,
                            ];
                        }

                        $checkStockTransaction = StockTransaction::query()
                            ->where('stock_adjustment_item_id', $stockAdjustmentItem_id)
                            ->where('entry_status', $entry_status)
                            ->first();

                        if (!empty($checkStockTransaction)) {
                            StockTransaction::where('id', $checkStockTransaction->id)->update($data_stock);
                        } else {
                            StockTransaction::create($data_stock);
                        }
                    } else {
                        // REJECTED
                        $checkStockTransaction = StockTransaction::query()
                            ->where('stock_adjustment_item_id', $stockAdjustmentItem_id)
                            ->where('entry_status', $entry_status)
                            ->first();

                        if (!empty($checkStockTransaction)) {
                            // B : Update Stock Transaction
                            StockTransaction::where('id', $checkStockTransaction->id)->update(['status' => StockTransaction::STATUS_CANCEL]);

                            // B : Update Stock Transaction Goods Value
                            StockTransactionOutGoodsValue::where('out_stock_adjustment_item_id', $stockAdjustmentItem_id)->update(['status' => StockTransactionOutGoodsValue::CANCEL]);

                            // B : Update Stock Transaction Goods Value
                            $stockTransaction = StockTransactionOutGoodsValue::where('out_stock_adjustment_item_id', $stockAdjustmentItem_id)->get();
                            foreach ($stockTransaction as $transaction) {
                                StockTransaction::where('id', $transaction->stock_transaction_id)->update([
                                    'stock_status' => StockTransaction::STOCK_AVAILABLE,
                                    'stock_out' => getStockTransaction($transaction->stock_transaction_id, 'out'),
                                    'stock_current' => getStockTransaction($transaction->stock_transaction_id, 'current'),
                                ]);
                            }
                        }
                    }
                    // E : Stock Transaction

                    // B : Update Stock Item
                    Item::where('code', $item_code)->update(['current_stock' => getItemStock($item_code)]);
                    $status =  StockAdjustmentItem::DONE;
                } else {
                    $status =  StockAdjustmentItem::PROCESS;
                }

                $data = [
                    'approvals_status' => $approvals_status,
                    'approvals_note' => $approvals_note,
                    'approvals_date' => Carbon::now(),
                    'approvals_by' => Auth::user()->employee_nik,
                    'status' => $status,
                ];
                StockAdjustmentItem::where('id', $stockAdjustmentItem_id)->update($data);

                // Update Adjustment Parent
                $approvalsPending  = $this->checkRowItem($stock_adjustment_code, '=', StockAdjustmentItem::APPROVALS_PENDING);
                $approvalsProcess  = $this->checkRowItem($stock_adjustment_code, '<>', StockAdjustmentItem::APPROVALS_PENDING);
                $approvalsApproved = $this->checkRowItem($stock_adjustment_code, '=', StockAdjustmentItem::APPROVALS_APPROVED);
                $approvalsRejected = $this->checkRowItem($stock_adjustment_code, '=', StockAdjustmentItem::APPROVALS_REJECTED);
                $approvalsAll      = $this->checkRowItem($stock_adjustment_code, '=', 'all');

                if ($approvalsPending>0 and $approvalsProcess>0) {
                    $status = StockAdjustment::PROCESS_APPROVALS;
                } elseif ($approvalsPending>0 and $approvalsProcess<=0) {
                    $status = StockAdjustment::REQUEST;
                } else {
                    if ($stockAdjustmentType==2) {
                        $status = StockAdjustment::DONE;
                    } else {
                        if ($approvalsApproved>0) {
                            $status = StockAdjustment::WAITING_REPORT;
                        } else {
                            if ($approvalsRejected==$approvalsAll) {
                                $status = StockAdjustment::REJECTED;
                            } else {
                                $status = StockAdjustment::DONE;
                            }
                        }
                    }
                }

                StockAdjustment::where('code', $stock_adjustment_code)->update(['status' => $status]);

                // Update status Quarantine/Dead Stock
                if ($stock_quarantine_id>0) {
                    $approvals = ($approvals_status==StockAdjustmentItem::APPROVALS_APPROVED) ? StockQuarantine::APPROVED : StockQuarantine::REJECTED;
                    StockQuarantine::where('id', $stock_quarantine_id)
                        ->update([
                            'approvals' => $approvals,
                            'status' => StockQuarantine::APPROVALS,
                        ]);
                } elseif ($stock_opname_id>0) {
                    $approvals = ($approvals_status==StockAdjustmentItem::APPROVALS_APPROVED) ? StockOpname::APPROVALS_APPROVED : StockOpname::APPROVALS_REJCTED;
                    StockOpname::where('id', $stock_opname_id)
                        ->update([
                            'approvals_status' => $approvals,
                            'approvals_by' => Auth::user()->employee_nik,
                        ]);
                }

                DB::commit();
                return response()->json(['message' => __('Berhasil menyimpan data persetujuan')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function approvalsMultiPerItemUpdate(Request $request)
    {
        $items              = $request->input('items');
        $approvals_status   = $request->input('approvals_status');
        $approvals_note     = "-";
        if (count($items)>0) {
            DB::beginTransaction();
            try {
                foreach ($items as $item) {
                    $id = $item['id'];
                    if ($stockAdjustmentItem = StockAdjustmentItem::find($id)) {
                        $stock_adjustment_code   = $stockAdjustmentItem->stock_adjustment_code;
                        $stockAdjustmentType     = getStockAdjustmentType($stock_adjustment_code);
                        $stock_adjustment_status = getStockAdjustmentCategoryStock($stockAdjustmentItem->stock_adjustment_category_id);
                        $entry_status            = ($stock_adjustment_status==1) ? StockTransaction::STOCK_IN : StockTransaction::STOCK_OUT;
                        $stock_status            = ($stock_adjustment_status==1) ? StockTransaction::STOCK_AVAILABLE : StockTransaction::STOCK_EMPTY;
                        $item_code               = $stockAdjustmentItem->item_code;
                        $stockAdjustmentItem_id  = $stockAdjustmentItem->id;
                        $stock_quarantine_id     = $stockAdjustmentItem->stock_quarantine_id;
                        $stock_opname_id         = $stockAdjustmentItem->stock_opname_id;

                        $stock_adjustment_type_value = getStockAdjustmentCategoryTypeValue($stockAdjustmentItem->stock_adjustment_category_id);
                        $transaction_category        = ($stock_adjustment_type_value==2) ? StockTransactionOutGoodsValue::PENALTY_VALUE : StockTransactionOutGoodsValue::GOODS_VALUE;

                        // Update stock untuk penyesuaian stock dengan type= 2 (Stock Opname)
                        if ($stockAdjustmentType==2) {
                            // B : Stock Transaction
                            if ($approvals_status==StockAdjustmentItem::APPROVALS_APPROVED) {
                                    // Save perhitungan biaya barang
                                    if ($entry_status==StockTransaction::STOCK_OUT) {
                                        $data_stock = [
                                            'item_code' => $item_code,
                                            'quantity' => $stockAdjustmentItem->quantity,
                                            'item_unit_conversion_id' => $stockAdjustmentItem->item_unit_conversion_id,
                                            'transaction_symbol' => "SA",
                                            'transaction_name' => "Penyesuaian Stok",
                                            'transaction_code' => $stockAdjustmentItem->stock_adjustment_code,
                                            'transaction_date' => date('Y-m-d H:i:s'),
                                            'stock_adjustment_item_id' => $stockAdjustmentItem_id,
                                            'entry_status' => $entry_status,
                                            'status' => StockTransaction::STATUS_DONE,
                                            'stock_status' => $stock_status,
                                        ];

                                        $setData  = [
                                            'item_code' => $item_code,
                                            'transaction_symbol' => "SA",
                                            'transaction_name' => "Penyesuaian Stok",
                                            'transaction_code' => $stockAdjustmentItem->stock_adjustment_code,
                                            'transaction_date' => date('Y-m-d H:i:s'),
                                            'transaction_category' => $transaction_category,
                                            'out_quantity' => $stockAdjustmentItem->quantity,
                                            'out_item_unit_conversion_id' => $stockAdjustmentItem->item_unit_conversion_id,
                                            'out_ic_goods_request_item_id' => 0,
                                            'out_ic_goods_request_item_out_id' => 0,
                                            'out_stock_adjustment_item_id' => $stockAdjustmentItem_id,
                                            'out_ic_goods_borrow_item_id' => 0,
                                            'out_goods_return_code' => 0,
                                            'out_goods_repairment_code' => 0,
                                            'borrow_status' => StockTransactionOutGoodsValue::BORROW_NOT,
                                            'repairment_status' => StockTransactionOutGoodsValue::REPAIRMENT_NOT,

                                        ];
                                        createOutStockGoodsValue($stockAdjustmentItem->quantity, $stockAdjustmentItem->item_unit_conversion_id, $setData);
                                    } else {
                                        $data_stock = [
                                            'item_code' => $item_code,
                                            'quantity' => $stockAdjustmentItem->quantity,
                                            'stock_out' => 0,
                                            'stock_current' => $stockAdjustmentItem->quantity,
                                            'item_unit_conversion_id' => $stockAdjustmentItem->item_unit_conversion_id,
                                            'transaction_symbol' => "SA",
                                            'transaction_name' => "Penyesuaian Stok",
                                            'transaction_code' => $stockAdjustmentItem->stock_adjustment_code,
                                            'transaction_date' => date('Y-m-d H:i:s'),
                                            'stock_adjustment_item_id' => $stockAdjustmentItem_id,
                                            'entry_status' => $entry_status,
                                            'status' => StockTransaction::STATUS_DONE,
                                            'stock_status' => $stock_status,
                                        ];
                                    }

                                    $checkStockTransaction = StockTransaction::query()
                                        ->where('stock_adjustment_item_id', $stockAdjustmentItem_id)
                                        ->where('entry_status', $entry_status)
                                        ->first();

                                    if (!empty($checkStockTransaction)) {
                                        StockTransaction::where('id', $checkStockTransaction->id)->update($data_stock);
                                    } else {
                                        StockTransaction::create($data_stock);
                                    }
                            } else {
                                    // REJECTED
                                    $checkStockTransaction = StockTransaction::query()
                                        ->where('stock_adjustment_item_id', $stockAdjustmentItem_id)
                                        ->where('entry_status', $entry_status)
                                        ->first();

                                    if (!empty($checkStockTransaction)) {
                                        // B : Update Stock Transaction
                                        StockTransaction::where('id', $checkStockTransaction->id)->update(['status' => StockTransaction::STATUS_CANCEL]);

                                        // B : Update Stock Transaction Goods Value
                                        StockTransactionOutGoodsValue::where('out_stock_adjustment_item_id', $stockAdjustmentItem_id)->update(['status' => StockTransactionOutGoodsValue::CANCEL]);

                                        // B : Update Stock Transaction Goods Value
                                        $stockTransaction = StockTransactionOutGoodsValue::where('out_stock_adjustment_item_id', $stockAdjustmentItem_id)->get();
                                        foreach ($stockTransaction as $transaction) {
                                            StockTransaction::where('id', $transaction->stock_transaction_id)->update([
                                                'stock_status' => StockTransaction::STOCK_AVAILABLE,
                                                'stock_out' => getStockTransaction($transaction->stock_transaction_id, 'out'),
                                                'stock_current' => getStockTransaction($transaction->stock_transaction_id, 'current'),
                                            ]);
                                        }
                                    }
                            }
                            // E : Stock Transaction

                            // B : Update Stock Item
                            Item::where('code', $item_code)->update(['current_stock' => getItemStock($item_code)]);
                            $status =  StockAdjustmentItem::DONE;
                        } else {
                            $status =  StockAdjustmentItem::PROCESS;
                        }

                        // Update Approvals Item
                        $data = [
                            'approvals_status' => $approvals_status,
                            'approvals_note' => $approvals_note,
                            'approvals_date' => Carbon::now(),
                            'approvals_by' => Auth::user()->employee_nik,
                            'status' => $status,
                        ];
                        StockAdjustmentItem::where('id', $stockAdjustmentItem_id)->update($data);

                        // Update Adjustment Parent
                        // B : Update Adjustment
                        $approvalsPending  = $this->checkRowItem($stock_adjustment_code, '=', StockAdjustmentItem::APPROVALS_PENDING);
                        $approvalsProcess  = $this->checkRowItem($stock_adjustment_code, '<>', StockAdjustmentItem::APPROVALS_PENDING);
                        $approvalsApproved = $this->checkRowItem($stock_adjustment_code, '=', StockAdjustmentItem::APPROVALS_APPROVED);
                        $approvalsRejected = $this->checkRowItem($stock_adjustment_code, '=', StockAdjustmentItem::APPROVALS_REJECTED);
                        $approvalsAll      = $this->checkRowItem($stock_adjustment_code, '=', 'all');

                        if ($approvalsPending>0 and $approvalsProcess>0) {
                            $status = StockAdjustment::PROCESS_APPROVALS;
                        } elseif ($approvalsPending>0 and $approvalsProcess<=0) {
                            $status = StockAdjustment::REQUEST;
                        } else {
                            if ($stockAdjustmentType==2) {
                                $status = StockAdjustment::DONE;
                            } else {
                                if ($approvalsApproved>0) {
                                    $status = StockAdjustment::WAITING_REPORT;
                                } else {
                                    if ($approvalsRejected==$approvalsAll) {
                                        $status = StockAdjustment::REJECTED;
                                    } else {
                                        $status = StockAdjustment::DONE;
                                    }
                                }
                            }
                        }

                        StockAdjustment::where('code', $stock_adjustment_code)->update(['status' => $status]);

                        // Update status Quarantine/Dead Stock
                        if ($stock_quarantine_id>0) {
                            $approvals = ($approvals_status==StockAdjustmentItem::APPROVALS_APPROVED) ? StockQuarantine::APPROVED : StockQuarantine::REJECTED;
                            StockQuarantine::where('id', $stock_quarantine_id)
                                ->update([
                                    'approvals' => $approvals,
                                    'status' => StockQuarantine::APPROVALS,
                                ]);
                        } elseif ($stock_opname_id>0) {
                            $approvals = ($approvals_status==StockAdjustmentItem::APPROVALS_APPROVED) ? StockOpname::APPROVALS_APPROVED : StockOpname::APPROVALS_REJCTED;
                            StockOpname::where('id', $stock_opname_id)
                                ->update([
                                    'approvals_status' => $approvals,
                                    'approvals_by' => Auth::user()->employee_nik,
                                ]);
                        }
                    }
                }
                DB::commit();
                return response()->json(['message' => __('Berhasil menyimpan data persetujuan')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function report(Request $request, $code)
    {
        if ($stockAdjustment = StockAdjustment::find($code)) {

            $request->validate([
                'release_by.nik' => 'required',
                'release_date' => 'required',
            ]);

            $release_date = $request->input('release_date');

            $stockAdjustmentItem = StockAdjustmentItem::query()
                ->where('approvals_status', StockAdjustmentItem::APPROVALS_APPROVED)
                ->where('stock_adjustment_code', $code)
                ->get();

            foreach ($stockAdjustmentItem as $sa) {
                // Update Stock Quarantine / Dead Stock
                $item_code                   = $sa['item_code'];
                $quantity                    = $sa['quantity'];
                $item_unit_conversion_id     = $sa['item_unit_conversion_id'];
                $stock_adjustment_item_id    = $sa['id'];
                $stock_adjustment_status     = getStockAdjustmentCategoryStock($sa['stock_adjustment_category_id']);
                $stock_adjustment_type_value = getStockAdjustmentCategoryTypeValue($sa['stock_adjustment_category_id']);
                $transaction_category        = ($stock_adjustment_type_value==2) ? StockTransactionOutGoodsValue::PENALTY_VALUE : StockTransactionOutGoodsValue::GOODS_VALUE;
                $entry_status                = ($stock_adjustment_status==1) ? StockTransaction::STOCK_IN : StockTransaction::STOCK_OUT;
                $stock_status                = ($stock_adjustment_status==1) ? StockTransaction::STOCK_AVAILABLE : StockTransaction::STOCK_EMPTY;

                if ($stockAdjustment->type==StockAdjustment::NORMAL OR $stockAdjustment->type==StockAdjustment::DEADSTOCK) {
                    // STOK TRANSACTION
                    if ($stockAdjustment->status<5 && $stockAdjustment->status<>4) {

                        // Save perhitungan biaya barang
                        if ($entry_status==StockTransaction::STOCK_OUT) {
                            $data_stock = [
                                'item_code' => $item_code,
                                'quantity' => $quantity,
                                'item_unit_conversion_id' => $item_unit_conversion_id,
                                'transaction_symbol' => "SA",
                                'transaction_name' => "Penyesuaian Stok",
                                'transaction_code' => $code,
                                'transaction_date' => $release_date,
                                'stock_adjustment_item_id' => $stock_adjustment_item_id,
                                'entry_status' => $entry_status,
                                'status' => StockTransaction::STATUS_DONE,
                                'stock_status' => $stock_status,
                            ];

                            $setData  = [
                                'item_code' => $item_code,
                                'transaction_symbol' => "SA",
                                'transaction_name' => "Penyesuaian Stok",
                                'transaction_code' => $code,
                                'transaction_date' => $release_date,
                                'transaction_category' => $transaction_category,
                                'out_quantity' => $quantity,
                                'out_item_unit_conversion_id' => $item_unit_conversion_id,
                                'out_ic_goods_request_item_id' => 0,
                                'out_ic_goods_request_item_out_id' => 0,
                                'out_stock_adjustment_item_id' => $stock_adjustment_item_id,
                                'out_ic_goods_borrow_item_id' => 0,
                                'out_goods_return_code' => 0,
                                'out_goods_repairment_code' => 0,
                                'borrow_status' => StockTransactionOutGoodsValue::BORROW_NOT,
                                'repairment_status' => StockTransactionOutGoodsValue::REPAIRMENT_NOT,

                            ];
                            createOutStockGoodsValue($quantity, $item_unit_conversion_id, $setData);
                        } else {
                            $data_stock = [
                                'item_code' => $item_code,
                                'quantity' => $quantity,
                                'stock_out' => 0,
                                'stock_current' => $quantity,
                                'item_unit_conversion_id' => $item_unit_conversion_id,
                                'transaction_symbol' => "SA",
                                'transaction_name' => "Penyesuaian Stok",
                                'transaction_code' => $code,
                                'transaction_date' => $release_date,
                                'stock_adjustment_item_id' => $stock_adjustment_item_id,
                                'entry_status' => $entry_status,
                                'status' => StockTransaction::STATUS_DONE,
                                'stock_status' => $stock_status,
                            ];
                        }

                        $checkStockTransaction = StockTransaction::query()
                            ->where('stock_adjustment_item_id', $stock_adjustment_item_id)
                            ->where('entry_status', $entry_status)
                            ->first();

                        if (!empty($checkStockTransaction)) {
                            StockTransaction::where('id', $checkStockTransaction->id)->update($data_stock);
                        } else {
                            StockTransaction::create($data_stock);
                        }

                        // B : Update Stock Item
                        Item::where('code', $item_code)->update(['current_stock' => getItemStock($item_code)]);
                    }

                    if ($sa->stock_quarantine_id>0) {
                        StockQuarantine::where('id', $sa->stock_quarantine_id)
                        ->update([
                            'status' => StockQuarantine::DONE,
                        ]);
                    }

                }

                $data = [
                    'release_date' => $request->input('release_date'),
                    'release_by' => $request->input('release_by.nik'),
                    'release_note' => $request->input('release_note'),
                    'status' => StockAdjustmentItem::DONE,
                ];

                StockAdjustmentItem::where('id', $stock_adjustment_item_id)->update($data);
            }

            $data = ['status' => StockAdjustment::DONE];
            $stockAdjustment->update($data);

            return response()->json(['message' => __('Berhasil menyimpan laporan penyesuaian stok.')]);
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function exportPdf(Request $request)
    {
        $code = $request->code;
        $data = StockAdjustment::with([
                'stockAdjustmentItem',
            ])
            ->where('code', $code)
            ->first();


        //return response()->json($data);
        //return view('stock::stock.export_pdf', compact('data'));

        $pdf = PDF::loadView('stock::stock.export_pdf', compact('data'));

        return $pdf->download();
    }

    public function checkRowItem($code, $operation, $approvals_status)
    {
        $query = StockAdjustmentItem::query();

        if ($approvals_status!="all") {
            $query = $query->where('approvals_status', $operation, $approvals_status);
        }

        $data = $query
            ->where('is_active',1)
            ->where('stock_adjustment_code',$code)
            ->get();
        return $data->count();
    }
}
