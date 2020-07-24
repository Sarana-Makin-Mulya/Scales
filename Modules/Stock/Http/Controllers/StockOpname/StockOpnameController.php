<?php

namespace Modules\Stock\Http\Controllers\StockOpname;

use App\Imports\StockOpnameImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\StockAdjustment;
use Modules\Stock\Entities\StockAdjustmentItem;
use Modules\Stock\Entities\StockOpname;
use Modules\Stock\Entities\StockOpnameGroup;
use Modules\Stock\Transformers\StockOpname\ItemStockOpnameDailyResource;
use Modules\Stock\Transformers\StockOpname\ItemStockOpnameExcelResource;

class StockOpnameController extends Controller
{
    public function index()
    {
        //return getQuantityConversionAll('LSTK0000137', 1);
        return view('stock::stockopname.index');
    }

    public function daily()
    {
        $pageName = "Stok Opname Harian";
        $filterName = "daily";
        return view('stock::stockopname.index', compact('pageName', 'filterName'));
    }

    public function period()
    {
        $pageName = "Stok Opname Periode";
        $filterName = "period";
        return view('stock::stockopname.index', compact('pageName', 'filterName'));
    }

    public function generateStockOpnameDaily()
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

                $item = Item::query()
                    ->select('code', 'current_stock', 'updated_at')
                    ->where('is_active',1)
                    ->where('current_stock','<', 1000)
                    ->orderBy('current_stock', 'DESC')
                    ->orderBy('updated_at', 'DESC')
                    ->skip(0)
                    ->take(10)
                    ->get();

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

    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required',
            'quantity_prev' => 'required',
            'quantity_new' => 'required',
            'item_unit_conversion_id.id' => 'required',
            'note' => 'required',
        ]);

        $item_unit_conversion_id = $request->input('item_unit_conversion_id');
        $quantity_prev = $request->input('quantity_prev');
        $quantity_new  = ($request->input('quantity_new')==null) ? 0 : $request->input('quantity_new');

        if ($quantity_prev==$quantity_new) {
            $stock_status   = StockOpname::STOCK_BALANCE;
            $status         = StockOpname::DONE;
            $quantity_issue = 0;
        } elseif ($quantity_prev>$quantity_new) {
            $stock_status   = StockOpname::STOCK_MIN;
            $status         = StockOpname::ENTRY;
            $quantity_issue = $quantity_prev-$quantity_new;
        } else {
            $stock_status   = StockOpname::STOCK_PLUS;
            $status         = StockOpname::ENTRY;
            $quantity_issue = $quantity_new-$quantity_prev;
        }

        $data = [
            'item_code' => $request->input('item_code'),
            'stockopname_type' => 'item',
            'item_unit_conversion_id' => $item_unit_conversion_id['id'],
            'quantity_prev' => $request->input('quantity_prev'),
            'quantity_new' => $request->input('quantity_new'),
            'quantity_issue' => $quantity_issue,
            'note' => $request->input('note'),
            'issue_date' => date('Y-m-d H:i:s'),
            'issued_by' => Auth::user()->employee_nik,
            'status' => $status,
            'stock_status' => $stock_status,
        ];

        DB::beginTransaction();
        try {
            $StockOpname = new StockOpname();
            $StockOpname->create($data);
            DB::commit();
            return response()->json(['message' => __('Berhasil menambahkan data stock opname.')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        if ($stockOpname=StockOpname::find($id)) {
            $request->validate([
                'item_code' => 'required',
                'item_unit_conversion_id.id' => 'required',
                'quantity_prev' => 'required',
                'quantity_new' => 'required',
                'note' => 'required',
            ]);

            $item_unit_conversion_id = $request->input('item_unit_conversion_id');
            $quantity_prev = $request->input('quantity_prev');
            $quantity_new  = ($request->input('quantity_new')==null) ? 0 : $request->input('quantity_new');

            if ($quantity_prev==$quantity_new) {
                $stock_status   = StockOpname::STOCK_BALANCE;
                $status         = StockOpname::DONE;
                $quantity_issue = 0;
            } elseif ($quantity_prev>$quantity_new) {
                $stock_status   = StockOpname::STOCK_MIN;
                $status         = StockOpname::ENTRY;
                $quantity_issue = $quantity_prev-$quantity_new;
            } else {
                $stock_status   = StockOpname::STOCK_PLUS;
                $status         = StockOpname::ENTRY;
                $quantity_issue = $quantity_new-$quantity_prev;
            }

            $data = [
                'item_code' => $request->input('item_code'),
                'item_unit_conversion_id' => $item_unit_conversion_id['id'],
                'quantity_prev' => $request->input('quantity_prev'),
                'quantity_new' => $request->input('quantity_new'),
                'quantity_issue' => $quantity_issue,
                'note' => $request->input('note'),
                'issue_date' => date('Y-m-d H:i:s'),
                'issued_by' => Auth::user()->employee_nik,
                'status' => $status,
                'stock_status' => $stock_status,
            ];

            DB::beginTransaction();
            try {
                $stockOpname->update($data);
                DB::commit();
                return response()->json(['message' => __('Berhasil mengubah data stock opname.')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function storeAdjustment(Request $request)
    {
        $stockopname_group_id = $request->stockopname_group_id;
        $stockopname_id = $request->stockopname_id;
        $quantity_issue = $request->quantity_issue;
        $quantity_adjustment = $request->quantity_adjustment;

        $request->validate([
            'stockopname_id' => 'required',
            'quantity_issue' => 'required',
            'quantity_adjustment' => 'required',
            'items.*.item_code' => 'required',
            'items.*.item_unit_conversion_id.id' => 'required',
            'items.*.quantity' => 'required',
            'items.*.stock_adjustment_category_id.id' => 'required',
        ]);

        $data = [
            'code' => generateCodeAdjustment(),
            'type' => StockAdjustment::STOCKOPNAME,
            'issue_date' => Carbon::now(),
            'issued_by' => Auth::user()->employee_nik,
            'description' => $request->input('description'),
            'stock_opname_group_id' => $stockopname_group_id,
            'stock_opname_id' => $stockopname_id,
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
                        'item_code' => $item['item_code'],
                        'item_unit_conversion_id' => $item['item_unit_conversion_id']['id'],
                        'quantity' => $item['quantity'],
                        'stock_adjustment_category_id' => $item['stock_adjustment_category_id']['id'],
                        'description' => $item['description'],
                        'stock_opname_id' => $stockopname_id,
                        'stock_opname_group_id' => $stockopname_group_id,
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

            StockOpname::where('id', $stockopname_id)->update([
                'quantity_issue' => $quantity_issue,
                'quantity_adjustment' => $quantity_adjustment,
                'approvals_status' => StockOpname::APPROVALS_WAITING,
                'status' => StockOpname::APPROVALS,
                'data_status' => StockOpname::DATA_LOCK,
            ]);

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
                'message' => 'Stock Opname : '.$save->code,
                'url' => 'po/service-request',
                'status' => 'new',
                'issued_by' => Auth::user()->employee_nik,
            ]);

            // 5 : Inventory Control
            createNotification([
                'notification_group_id' => 5,
                'type' => 'notification',
                'transaction_type' => 'sa',
                'transaction_code' => $save->code,
                'transaction_id' => null,
                'from' => 'gudang-umum',
                'to' => 'inventory-control',
                'message' => 'Stock Opname : '.$save->code,
                'url' => 'po/service-request',
                'status' => 'new',
                'issued_by' => Auth::user()->employee_nik,
            ]);

            DB::commit();
            return response()->json(['message' => __('Berhasil menambahkan data pertanggungjawaban stok.')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function updateAdjustment(Request $request, $id)
    {
        $findcode = StockAdjustment::where('stock_opname_id',$id)->first();
        $code     = $findcode->code;
        if ($stockAdjustment = StockAdjustment::find($code)) {
            $stockopname_group_id = $request->stockopname_group_id;
            $stockopname_id = $request->stockopname_id;
            $quantity_issue = $request->quantity_issue;
            $quantity_adjustment = $request->quantity_adjustment;

            $request->validate([
                'stockopname_id' => 'required',
                'quantity_issue' => 'required',
                'quantity_adjustment' => 'required',
                'items.*.item_code' => 'required',
                'items.*.item_unit_conversion_id.id' => 'required',
                'items.*.quantity' => 'required',
                'items.*.stock_adjustment_category_id.id' => 'required',
            ]);

            $data = [
                'stock_opname_group_id' => $stockopname_group_id,
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
                            $data = [
                                'stock_adjustment_code' => $code,
                                'item_code' => $item['item_code'],
                                'item_unit_conversion_id' => $item['item_unit_conversion_id']['id'],
                                'quantity' => $item['quantity'],
                                'stock_adjustment_category_id' => $item['stock_adjustment_category_id']['id'],
                                'description' => $item['description'],
                                'stock_opname_id' => $stockopname_id,
                                'stock_opname_group_id' => $stockopname_group_id,
                                'is_active' => 1,
                                'approvals_status' => StockAdjustmentItem::APPROVALS_PENDING,
                                'status' => StockAdjustmentItem::REQUEST,
                                'data_status' => StockAdjustmentItem::DATA_OPEN,
                            ];

                            if (empty($item['id'])) {
                                $stockAdjustment->stockAdjustmentItem()->create($data);
                            } else {
                                $stockAdjustment->stockAdjustmentItem()->where('id', $item['id'])->update($data);
                            }
                        }
                    }

                    StockAdjustmentItem::where('stock_adjustment_code', $code)->where('is_active', 0)->delete();
                    StockOpname::where('id', $stockopname_id)->update([
                        'quantity_issue' => $quantity_issue,
                        'quantity_adjustment' => $quantity_adjustment,
                        'status' => StockOpname::APPROVALS,
                        'data_status' => StockOpname::DATA_LOCK,
                    ]);

                    DB::commit();
                    return response()->json(['message' => __('Berhasil mengubah data pertanggungjawaban stok.')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    public function importExcel(Request $request)
    {

        $request->validate([
            'file'  => 'required|mimes:xls,xlsx'
        ]);

        $file     = $request->file('file');
        $fileName = $file->getClientOriginalName();

        $data = Excel::toArray(new StockOpnameImport, $file);
        if (count($data[0]) > 0) {
            DB::beginTransaction();
            try {
                $stockOpnameGroup = StockOpnameGroup::create([
                    'file_name' => $fileName,
                    'type' => 'general',
                    'issued_by' => Auth::user()->employee_nik,
                    'issue_date' => Carbon::now(),
                ]);

                foreach ($data[0] as $row) {
                    if ($row['stok_baru']=="0.0" or $row['stok_baru']>0) {
                        $item_unit_conversion = getUnitConversionId($row['kode']);

                        $quantity_prev = $row['stok'];
                        $quantity_new  = $row['stok_baru'];

                        if ($quantity_prev==$quantity_new) {
                            $stock_status   = StockOpname::STOCK_BALANCE;
                            $status         = StockOpname::DONE;
                            $quantity_issue = 0;
                        } elseif ($quantity_prev>$quantity_new) {
                            $stock_status   = StockOpname::STOCK_MIN;
                            $status         = StockOpname::ENTRY;
                            $quantity_issue = $quantity_prev-$quantity_new;
                        } else {
                            $stock_status   = StockOpname::STOCK_PLUS;
                            $status         = StockOpname::ENTRY;
                            $quantity_issue = $quantity_new-$quantity_prev;
                        }

                        $dataStockOpname = [
                            'stock_opname_group_id'=> $stockOpnameGroup->id,
                            'stockopname_type' => 'general',
                            'item_unit_conversion_id' => $item_unit_conversion['id'],
                            'item_code' => $row['kode'],
                            'quantity_prev' => $row['stok'],
                            'quantity_new' => $row['stok_baru'],
                            'quantity_issue' => $quantity_issue,
                            'issue_date' => Carbon::now(),
                            'issued_by' => Auth::user()->employee_nik,
                            'status' => $status,
                            'stock_status' => $stock_status,
                        ];

                        StockOpname::create($dataStockOpname);
                    }
                }
                DB::commit();
                return response()->json(['message' => __('Berhasil upload data stock opname.')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Upload data stock opname gagal'], 400);
    }

    public function storeStockOpnameDaily(Request $request)
    {
        $item = $request->items;
        if (count($item) > 0) {
            DB::beginTransaction();
            try {
                foreach ($item as $row) {
                    $id            = $row['id'];
                    $quantity_prev = $row['quantity_prev'];
                    $quantity_new  = $row['quantity_new'];

                    if ($quantity_prev==$quantity_new) {
                        $stock_status   = StockOpname::STOCK_BALANCE;
                        $status         = StockOpname::DONE;
                        $quantity_issue = 0;
                    } elseif ($quantity_prev>$quantity_new) {
                        $stock_status   = StockOpname::STOCK_MIN;
                        $status         = StockOpname::ENTRY;
                        $quantity_issue = $quantity_prev-$quantity_new;
                    } else {
                        $stock_status   = StockOpname::STOCK_PLUS;
                        $status         = StockOpname::ENTRY;
                        $quantity_issue = $quantity_new-$quantity_prev;
                    }

                    $dataStockOpname = [
                        'quantity_new' => $quantity_new,
                        'quantity_issue' => $quantity_issue,
                        'status' => $status,
                        'stock_status' => $stock_status,
                    ];

                    if ($quantity_new!=null) {
                        StockOpname::where('id', $id)->update($dataStockOpname);
                    }

                }
                DB::commit();
                return response()->json(['message' => __('Berhasil menyimpan data stockopname harian')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        } else {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
    }
}
