<?php

namespace Modules\Stock\Http\Controllers\Item;

use App\Imports\itemImport;
use App\Imports\itemStockImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\General\Entities\Unit;
use Modules\Stock\Entities\Brand;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\ItemCategory;
use Modules\Stock\Entities\ItemUnitConversion;
use Modules\Stock\Http\Requests\ItemRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Stock\Entities\StockOldApp;
use Modules\Stock\Entities\StockTransaction;
use Modules\StorageMap\Entities\StorageMapRackItem;
use PDF;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $categories = ItemCategory::where('is_active', 1)->orderBy('name', 'asc')->get();
        $brands     = Brand::orderBy('name', 'asc')->get();
        $units      = Unit::where('is_active', 1)->orderBy('name', 'asc')->get();
        return view('stock::item.index', compact('categories', 'brands', 'units'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('stock::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $item_category_id = $request->item_category_id;
        $unit_id = $request->input('unit_id');
        $last_row = Item::where('item_category_id',$item_category_id)->count()+1;

        $request->validate([
            'name' => 'required|string|max:255',
            'item_category_id' => 'required',
            'unit_id' => 'required',
        ]);

        $itemcode   = $this->generateItemCode($last_row, $item_category_id);
        $nickname   = trim(strtoupper($request->nickname));
        $name       = trim(strtoupper($request->name));
        $type       = trim(strtoupper($request->type));
        $size       = trim(strtoupper($request->size));
        $color      = trim(strtoupper($request->color));
        $brand      = trim(getBrandName($request->item_brand_id));
        $detail     = trim($name);
        $detail    .= (!empty($type)) ? " ".$type : "";
        $detail    .= (!empty($size)) ? " ".$size : "";
        $detail    .= (!empty($color)) ? " ".$color : "";
        $detail    .= (!empty($brand)) ? " ".$brand : "";
        $detail    .= (!empty($nickname)) ? " / ".$nickname : "";
        $info       = $itemcode." ".$detail;

        $data = [
            'code' => $itemcode,
            'name' => $name,
            'slug' => str::slug($detail),
            'nickname' => strtoupper($nickname),
            'item_category_id' => $item_category_id,
            'item_brand_id' => $request->item_brand_id,
            'type' => $type,
            'size' => $size,
            'color' => $color,
            'detail' => $detail,
            'info' => $info,
            'description' => $request->description,
            'is_priority' => $request->is_priority,
            'borrowable' => $request->borrowable,
            'min_stock' => $request->min_stock,
            'max_stock' => $request->max_stock,
            'status_stock' => 0,
            'stock_app_old_id' => 0,
        ];

        $checkItem = Item::where('detail', $detail)->where('item_category_id', $item_category_id)->first();
        if (empty($checkItem)) {
            DB::beginTransaction();
            try {
                $item = Item::create($data);
                $conversions = $request->conversions;
                $locations = $request->locations;

                // Unit Primary
                if (!empty($unit_id)) {
                    $item->unitConversion()->create([
                        'item_code' => $item->code,
                        'unit_id' => $unit_id,
                        'conversion_value' => 1,
                        'is_primary' => 1,
                    ]);
                }

                // Unit Conversion
                if (!empty($conversions)) {
                    foreach ($conversions as $convert) {
                        $item->unitConversion()->create([
                            'item_code' => $item->code,
                            'unit_id' => $convert['conversion_unit'],
                            'conversion_value' => $convert['conversion_value'],
                        ]);
                    }
                }

                // Location
                if (!empty($locations)) {
                    foreach ($locations as $location) {
                        StorageMapRackItem::create([
                            'storage_map_properties_id' => $location['storage_map_properties_id'],
                            'storage_map_rack_stage_id' => $location['storage_map_rack_stage_id'],
                            'item_code' => $item->code,
                            'description' => null,
                            'is_active' => 1,
                        ]);
                    }
                }

                // First Stock
                if ($request->first_stock['quantity']>0) {
                    // Save Stock Old APP
                    $qty_stock  = trim(strtoupper($request->first_stock['quantity']));
                    $unit_name  = trim(strtoupper(getUnitConversionName($request->first_stock['item_unit_conversion_id'])));

                    $dataItem = [
                        'item_old_code' => null,
                        'item_code' => $itemcode,
                        'name' => $name,
                        'size' => $size,
                        'tipe' => $type,
                        'brand' => $brand,
                        'color' => $color,
                        'qty_borrow' => 0,
                        'qty_stock' => $qty_stock,
                        'unit_name' => $unit_name,
                    ];

                    $checkItem = StockOldApp::where('item_code', $itemcode)->first();
                    if (!empty($checkItem)) {
                        $save = StockOldApp::where('item_code', $itemcode)->update($dataItem);
                        $stock_app_old_id = $checkItem->id;
                    } else {
                        $save = StockOldApp::create($dataItem);
                        $stock_app_old_id = $save->id;
                    }

                    if ($stock_app_old_id>0) {
                        $conversion = getUnitConversionId($itemcode);
                        // B : Stock Transaction
                        $checkStockTransaction = StockTransaction::query()
                            ->where('transaction_code', $stock_app_old_id)
                            ->where('item_code', $itemcode)
                            ->where('transaction_symbol', "OA")
                            ->first();
                        if (!empty($checkStockTransaction)) {
                            $data_stock = [
                                'item_code' => $itemcode,
                                'quantity' => $qty_stock,
                                'stock_out' => getStockTransaction($checkStockTransaction->id, 'out'),
                                'stock_current' => getStockTransaction($checkStockTransaction->id, 'current'),
                                'item_unit_conversion_id' => $conversion['id'],
                                'po_code' => null,
                                'transaction_symbol'=> "OA",
                                'transaction_name' => "Stok Aplikasi Lama",
                                'transaction_code' => $stock_app_old_id,
                                'transaction_date' => date('Y-m-d H:i:s'),
                                'delivery_order_item_id' => 0,
                                'entry_status' => StockTransaction::STOCK_IN,
                                'status' => StockTransaction::STATUS_DONE,
                                'data_status' => StockTransaction::DATA_OPEN,
                                'stock_status' => StockTransaction::STOCK_AVAILABLE,
                            ];
                            StockTransaction::where('id', $checkStockTransaction->id)->update($data_stock);
                        } else {
                            $data_stock = [
                                'item_code' => $itemcode,
                                'quantity' => $qty_stock,
                                'stock_out' => 0,
                                'stock_current' => $qty_stock,
                                'item_unit_conversion_id' => $conversion['id'],
                                'po_code' => null,
                                'transaction_symbol'=> "OA",
                                'transaction_name' => "Stok Aplikasi Lama",
                                'transaction_code' => $stock_app_old_id,
                                'transaction_date' => date('Y-m-d H:i:s'),
                                'delivery_order_item_id' => 0,
                                'entry_status' => StockTransaction::STOCK_IN,
                                'status' => StockTransaction::STATUS_DONE,
                                'data_status' => StockTransaction::DATA_OPEN,
                                'stock_status' => StockTransaction::STOCK_AVAILABLE,
                            ];
                            StockTransaction::create($data_stock);
                        }

                        // B : Update Stock Item
                        stockOldApp::where('id', $stock_app_old_id)->update([
                            'item_code' => $itemcode,
                        ]);

                        // B : Update Stock Item
                        Item::where('code', $itemcode)->update([
                            'current_stock' => getItemStock($itemcode),
                            'stock_app_old_id' => $stock_app_old_id,
                        ]);
                    }
                }

                DB::commit();

                return response()->json([
                    'code' => $item->code,
                    'changed' => true,
                    'act' => 'New',
                    'message' => __('Berhasil menambahkan data satuan baru.'),
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        } else {
            $message = "Data barang :".$detail." sudah terdaftar.!!!";
            return response()->json(['message' => $message], 422);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('stock::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('stock::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $code)
    {
        $last_code = $code;
        if ($item=Item::find($last_code)) {
            $request->validate([
                'name' => 'required|string|max:255',
                'item_category_id' => 'required',
                'unit_id' => 'required',
            ]);

            $unit_id = $request->input('unit_id');
            $item_category_id = $request->item_category_id;
            $nickname   = trim(strtoupper($request->nickname));
            $name       = trim(strtoupper($request->name));
            $type       = trim(strtoupper($request->type));
            $size       = trim(strtoupper($request->size));
            $color      = trim(strtoupper($request->color));
            $brand      = trim(getBrandName($request->item_brand_id));
            $detail     = trim($name);
            $detail    .= (!empty($type)) ? " ".$type : "";
            $detail    .= (!empty($size)) ? " ".$size : "";
            $detail    .= (!empty($color)) ? " ".$color : "";
            $detail    .= (!empty($brand)) ? " ".$brand : "";
            $detail    .= (!empty($nickname)) ? " / ".$nickname : "";
            $info       = $code." ".$detail;

            $data = [
                'name' => $name,
                'slug' => str::slug($detail),
                'nickname' => $nickname,
                'item_category_id' => $item_category_id,
                'item_brand_id' => $request->item_brand_id,
                'type' => $type,
                'size' => $size,
                'color' => $color,
                'detail' => $detail,
                'info' => $info,
                'description' => $request->description,
                'is_priority' => $request->is_priority,
                'borrowable' => $request->borrowable,
                'min_stock' => $request->min_stock,
                'max_stock' => $request->max_stock,
            ];

            $data_conversion = ['is_active' => 0];

            if ($item->item_category_id != $item_category_id) {
                $last_row = Item::where('item_category_id',$item_category_id)->count()+1;
                $code = $this->generateItemCode($last_row, $item_category_id);
                $data = Arr::add($data, 'code', $code);
                $data_conversion = Arr::add($data_conversion, 'item_code', $code);
            }

            $checkItem = Item::query()
                ->where('detail', $detail)
                ->where('item_category_id', $item_category_id)
                ->where('code', '<>', $last_code)
                ->first();
            if (empty($checkItem)) {
                DB::beginTransaction();
                try {
                    $item->update($data);
                    $conversions = $request->conversions;
                    $locations = $request->locations;
                    ItemUnitConversion::where('item_code', $last_code)->update($data_conversion);

                    // Unit Primary
                    if (!empty($unit_id)) {
                        $unit_primary = ItemUnitConversion::where('item_code',$last_code)->where('is_primary',1)->first();
                        if (!empty($unit_primary)) {
                            $item->unitConversion()->where('id', $unit_primary->id)->update([
                                'item_code' => $code,
                                'unit_id' => $unit_id,
                                'conversion_value' => 1,
                                'is_active' => 1,
                                'is_primary' => 1,
                            ]);
                        } else {
                            // Unit Primary
                            $item->unitConversion()->create([
                                'item_code' => $item->code,
                                'unit_id' => $unit_id,
                                'conversion_value' => 1,
                                'is_primary' => 1,
                            ]);
                        }
                    }

                    if (!empty($conversions)) {
                        foreach ($conversions as $convert) {
                            // Check jika ada data yang sama tetapi sudah tidak aktif maka di aktifkankembali
                            $check_convert = ItemUnitConversion::query()
                                ->where('item_code',$last_code)
                                ->where('unit_id',$convert['conversion_unit'])
                                ->where('conversion_value',$convert['conversion_value'])
                                ->where('is_active',0)
                                ->first();

                            $convert_id = empty($convert['id']) ? empty($check_convert) ? "" : $check_convert->id : $convert['id'];

                            if (empty($convert_id)) {
                                $item->unitConversion()->create([
                                    'item_code' => $code,
                                    'unit_id' => $convert['conversion_unit'],
                                    'conversion_value' => $convert['conversion_value'],
                                    'is_active' => 1,
                                ]);
                            } else {
                                $item->unitConversion()->where('id', $convert_id)->update([
                                    'item_code' => $code,
                                    'unit_id' => $convert['conversion_unit'],
                                    'conversion_value' => $convert['conversion_value'],
                                    'is_active' => 1,
                                ]);
                            }
                        }

                    }

                    // Location
                    if (!empty($locations)) {
                        StorageMapRackItem::where('item_code', $code)->update(['is_active' => 0]);
                        foreach ($locations as $location) {
                            $data = [
                                    'storage_map_properties_id' => $location['storage_map_properties_id'],
                                    'storage_map_rack_stage_id' => $location['storage_map_rack_stage_id'],
                                    'item_code' => $item->code,
                                    'description' => null,
                                    'is_active' => 1,
                                ];
                            $check = StorageMapRackItem::query()
                                ->where('storage_map_properties_id', $location['storage_map_properties_id'])
                                ->where('storage_map_rack_stage_id', $location['storage_map_rack_stage_id'])
                                ->where('item_code', $code)
                                ->first();

                            if (!empty($check)) {
                                StorageMapRackItem::where('id', $check->id)->update($data);
                            } else {
                                StorageMapRackItem::create($data);
                            }

                        }
                        StorageMapRackItem::where('is_active', 0)->where('item_code', $code)->delete();
                    } else {
                        StorageMapRackItem::where('item_code', $code)->update(['is_active' => 0]);
                        StorageMapRackItem::where('is_active', 0)->where('item_code', $code)->delete();
                    }

                    // First Stock

                    $qty_stock  = trim(strtoupper($request->first_stock['quantity']));
                    $unit_name  = trim(strtoupper(getUnitConversionName($request->first_stock['item_unit_conversion_id'])));

                    $dataItem = [
                        'item_code' => $code,
                        'name' => $name,
                        'size' => $size,
                        'tipe' => $type,
                        'brand' => $brand,
                        'color' => $color,
                        'qty_borrow' => 0,
                        'qty_stock' => $qty_stock,
                        'unit_name' => $unit_name,
                    ];
                    // Save Stock Old APP
                    $checkItem = StockOldApp::where('item_code', $code)->first();
                    if (!empty($checkItem)) {
                        $save = StockOldApp::where('item_code', $code)->update($dataItem);
                        $stock_app_old_id = $checkItem->id;
                    } else {
                        if ($request->first_stock['quantity']>0) {
                            $save = StockOldApp::create($dataItem);
                            $stock_app_old_id = $save->id;
                        } else {
                            $stock_app_old_id = null;
                        }
                    }

                    if ($stock_app_old_id>0) {
                        $conversion = getUnitConversionId($code);
                        // B : Stock Transaction
                        $checkStockTransaction = StockTransaction::query()
                            ->where('transaction_code', $stock_app_old_id)
                            ->where('item_code', $code)
                            ->where('transaction_symbol', "OA")
                            ->first();
                        
                        if (!empty($checkStockTransaction)) {
                            if ($checkStockTransaction->stock_out<=$qty_stock) {
                                $data_stock = [
                                    'item_code' => $code,
                                    'quantity' => $qty_stock,
                                    'item_unit_conversion_id' => $conversion['id'],
                                    'po_code' => null,
                                    'transaction_symbol'=> "OA",
                                    'transaction_name' => "Stok Aplikasi Lama",
                                    'transaction_code' => $stock_app_old_id,
                                    'transaction_date' => date('Y-m-d H:i:s'),
                                    'delivery_order_item_id' => 0,
                                    'entry_status' => StockTransaction::STOCK_IN,
                                    'status' => StockTransaction::STATUS_DONE,
                                    'data_status' => StockTransaction::DATA_OPEN,
                                    'stock_status' => StockTransaction::STOCK_AVAILABLE,
                                ];
                                StockTransaction::where('id', $checkStockTransaction->id)->update($data_stock);

                                $data_stock = [
                                    'stock_out' => getStockTransaction($checkStockTransaction->id, 'out'),
                                    'stock_current' => getStockTransaction($checkStockTransaction->id, 'current'),
                                ];
                                
                                StockTransaction::where('id', $checkStockTransaction->id)->update($data_stock);
                            } else {
                                DB::rollback();
                                $message = "Stok awal : '".$qty_stock."' kurang dari stok yang sudah keluar : '".$checkStockTransaction->stock_out."'";
                                return response()->json(['message' => $message], 422);
                            }


                        } else {
                            $data_stock = [
                                'item_code' => $code,
                                'quantity' => $qty_stock,
                                'stock_out' => 0,
                                'stock_current' => $qty_stock,
                                'item_unit_conversion_id' => $conversion['id'],
                                'po_code' => null,
                                'transaction_symbol'=> "OA",
                                'transaction_name' => "Stok Aplikasi Lama",
                                'transaction_code' => $stock_app_old_id,
                                'transaction_date' => date('Y-m-d H:i:s'),
                                'delivery_order_item_id' => 0,
                                'entry_status' => StockTransaction::STOCK_IN,
                                'status' => StockTransaction::STATUS_DONE,
                                'data_status' => StockTransaction::DATA_OPEN,
                                'stock_status' => StockTransaction::STOCK_AVAILABLE,
                            ];
                            StockTransaction::create($data_stock);
                        }

                        // B : Update Stock Item
                        StockOldApp::where('id', $stock_app_old_id)->update([
                            'item_code' => $code,
                        ]);

                        // B : Update Stock Item
                        Item::where('code', $code)->update([
                            'current_stock' => getItemStock($code),
                            'stock_app_old_id' => $stock_app_old_id,
                        ]);
                    }

                    DB::commit();

                    return response()->json([
                        'code' => $code,
                        'changed' => changeDetection($item),
                        'act' => 'Update',
                        'message' => __('Berhasil memperbaharui data barang.'),
                    ]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(['message' => $e->getMessage()], 400);
                }
            } else {
                $message = "Data barang :".$detail." sudah terdaftar.!!!";
                return response()->json(['message' => $message], 422);
            }
        }

        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function updateStatus(Request $request, $id)
    {
        $message = 'Berhasil menon-aktifkan data barang.';

        if ($request->status) {
            $message = 'Berhasil mengaktifkan data barang.';
        }

        if ($item = Item::find($id)) {
            $item->update([ 'is_active' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }

    public function updateStatusStock(Request $request, $id)
    {
        $message = 'Berhasil menghilangkan tanda stok barang sesuai.';

        if ($request->status) {
            $message = 'Berhasil menandai stok barang sesuai.';
        }

        if ($item = Item::find($id)) {
            $item->update([ 'status_stock' => $request->status]);
            return response()->json([ 'message' => $message ]);
        }

        return response()->json(['message' => 'Data tidak ditemukan']);
    }

    public function generateItemCode($number, $item_category_id)
    {
        $category  = ItemCategory::where('id', $item_category_id)->first();
        $category_code = empty($category->code) ? "XYZ" : $category->code;
        $item_code = $category_code.str_pad($number ,4,0,STR_PAD_LEFT);

        if (Item::withTrashed()->where('code', $item_code)->exists()) {
             $number++;
             return $this->generateItemCode($number, $item_category_id);
        }

        return $item_code;
    }

    public function exportPdf(Request $request)
    {
        $per_page = $request->per_page;
        $page = $request->page;

        $items = DB::table('item_categories')
            ->join('items', 'item_categories.id', '=', 'item_category_id')
            ->select('item_categories.name as category_name', 'items.code', 'items.detail', 'items.min_stock', 'items.borrowable')
            ->take($per_page*$page)
            ->get();

        $pdf = PDF::loadView('stock::item.export_pdf', ['items' => $items]);

        return $pdf->download();
    }

    public function updateTypoItem()
    {
        $data = Item::get();
        if ($data->count()>0) {
            DB::beginTransaction();
            try {
                foreach ($data as $dt) {
                    $itemcode    = $dt->code;
                    $description = trim(strtoupper($dt->description));
                    $nickname    = trim(strtoupper($dt->nickname));
                    $name        = trim(strtoupper($dt->name));
                    $type        = trim(strtoupper($dt->type));
                    $size        = trim(strtoupper($dt->size));
                    $color       = trim(strtoupper($dt->color));
                    $brand       = trim(getBrandName($dt->item_brand_id));
                    $detail      = trim($name);
                    $detail     .= (!empty($type)) ? " ".$type : "";
                    $detail     .= (!empty($size)) ? " ".$size : "";
                    $detail     .= (!empty($color)) ? " ".$color : "";
                    $detail     .= (!empty($brand)) ? " ".$brand : "";
                    $detail     .= (!empty($nickname)) ? " / ".$nickname : "";
                    $info        = $itemcode." ".$detail;

                    $data = [
                        'name' => $name,
                        'slug' => str::slug($detail),
                        'nickname' => $nickname,
                        'type' => $type,
                        'size' => $size,
                        'color' => $color,
                        'detail' => $detail,
                        'info' => $info,
                        'description' => $description,
                    ];
                    Item::where('code', $itemcode)->update($data);
                }
                DB::commit();
                return response()->json(['message' => __('Berhasil update data barang.')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Tidak terdapat data barang'], 400);
    }

    public function importItem(Request $request)
    {

        $request->validate([
            'file'  => 'required'
        ]);

        $file     = $request->file('file');
        $fileName = $file->getClientOriginalName();

       $data = Excel::toArray(new itemImport, $file);
        if (count($data[0]) > 0) {
            DB::beginTransaction();
            try {
                foreach ($data[0] as $row) {
                    $stock_code  = trim($row['stock_code']);
                    $stock_name  = trim($row['stock_name']);
                    $stock_type  = trim($row['stock_type']);
                    $stock_size  = trim($row['stock_size']);
                    $stock_color = trim($row['stock_color']);
                    $stock_brand = trim($row['stock_brand']);
                    $stock_description = trim($row['stock_description']);
                    $category_code = substr($stock_code,0,3);

                    if (!empty($category_code)) {
                        $categoryData = ItemCategory::where('old_code', $category_code)->first();
                        if (empty($categoryData)) {
                            $category_code = substr($stock_code,0,4);
                            $categoryData = ItemCategory::where('old_code', $category_code)->first();
                            //$categoryData = ItemCategory::create(['old_code' => $category_code]);
                        }
                        $category_id = (!empty($categoryData)) ? $categoryData->id : 0;
                    }

                    if (!empty($stock_brand) or $stock_brand!='') {
                        $checkBrand = Brand::query()
                            ->where('name', $stock_brand)
                            ->first();
                        if (empty($checkBrand)) {
                            $saveBrand = Brand::create([
                                'name' => $stock_brand,
                                'slug' => str::slug($stock_brand),
                                ]);
                            $item_brand_id = $saveBrand->id;
                        } else {
                            $item_brand_id = $checkBrand->id;
                        }
                    } else {
                        $item_brand_id = null;
                    }

                    $checkItem = Item::where('old_code', $row['stock_code'])->first();
                    if (!empty($checkItem)) {
                        $new_code   = $checkItem->code;
                        $detail     = trim($stock_name);
                        $detail    .= (!empty($stock_type)) ? " ".$stock_type : "";
                        $detail    .= (!empty($stock_size)) ? " ".$stock_size : "";
                        $detail    .= (!empty($stock_color)) ? " ".$stock_color : "";
                        $detail    .= (!empty(getBrandName($item_brand_id))) ? " ".getBrandName($item_brand_id) : "";
                        $info       = $new_code." ".$detail;

                        $dataItem = [
                            'old_code' => $row['stock_code'],
                            'item_category_id' => $category_id,
                            'item_brand_id' => $item_brand_id,
                            'item_measure_id' =>  $row['measure_code'],
                            'name' =>  $stock_name,
                            'slug' => str::slug($detail),
                            'nickname' => null,
                            'type' =>  $stock_type,
                            'size' =>  $stock_size,
                            'color' => $stock_color,
                            'detail' => $detail,
                            'description' => $stock_description,
                            'info' => $info,
                            'is_priority' => 0,
                            'borrowable' => 0,
                            'max_stock' => 0,
                            'min_stock' => 0,
                            'current_stock' => 0,
                            'status_stock' => 0,
                            'stock_app_old_id' => 0,
                            'is_active' => 1,
                            'create_by' => Auth::user()->employee_nik,
                        ];
                        $save = Item::where('old_code', $row['stock_code'])->update($dataItem);
                    } else {
                        $last_row   = Item::where('item_category_id', $category_id)->count()+1;
                        $new_code   = $this->generateItemCode($last_row, $category_id);
                        $detail     = trim($stock_name." ".$stock_type." ".$stock_size." ".$stock_color." ".getBrandName($item_brand_id));
                        $info       = $new_code." ".$stock_code." ".trim($stock_name." ".$stock_type." ".$stock_size." ".getBrandName($item_brand_id)." ".$stock_color.getCategoryName($category_id));

                        $dataItem = [
                            'code' =>  $new_code,
                            'old_code' =>  $row['stock_code'],
                            'item_category_id' => $category_id,
                            'item_brand_id' => $item_brand_id,
                            'item_measure_id' =>  $row['measure_code'],
                            'name' =>  $row['stock_name'],
                            'slug' => str::slug($detail),
                            'nickname' => null,
                            'type' =>  $row['stock_type'],
                            'size' =>  $row['stock_size'],
                            'color' => $row['stock_color'],
                            'detail' => $detail,
                            'description' => $row['stock_description'],
                            'info' => $info,
                            'is_priority' => 0,
                            'borrowable' => 0,
                            'max_stock' => 0,
                            'min_stock' => 0,
                            'current_stock' => 0,
                            'status_stock' => 0,
                            'stock_app_old_id' => 0,
                            'is_active' => 1,
                            'create_by' => Auth::user()->employee_nik,
                        ];
                        $save = Item::create($dataItem);
                    }

                    $unitData = Unit::where('measure_code', $row['measure_code'])->first();
                    if (!empty($unitData)) {
                        $ItemUnitConversion = ItemUnitConversion::query()
                            ->where('item_code', $new_code)
                            ->where('unit_id', $unitData->id)
                            ->first();

                        $item_unit_conversions = [
                            'item_code' => $new_code,
                            'unit_id' => $unitData->id,
                            'conversion_value' => 1,
                            'is_primary' => 1,
                            'is_active' => 1,
                        ];

                        if (!empty($ItemUnitConversion)) {
                            ItemUnitConversion::where('id', $ItemUnitConversion->id)->update($item_unit_conversions);
                        } else {
                            ItemUnitConversion::create($item_unit_conversions);
                        }
                    }
                    //break;
                }
                DB::commit();
                return response()->json(['message' => __('Berhasil upload data barang.')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
         return response()->json(['message' => 'Import item error'], 400);
    }

    public function importItemStock(Request $request)
    {
        $request->validate([
            'file'  => 'required'
        ]);

        $file     = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $item     = Item::get();
        if ($item->count()>0) {
            $data = Excel::toArray(new itemStockImport, $file);
            if (count($data[0]) > 0) {
                DB::beginTransaction();
                try {
                    foreach ($data[0] as $row) {
                        $item_old_code  = trim($row['kode_barang']);
                        $name           = trim($row['barang']);
                        $size           = trim($row['ukuran']);
                        $tipe           = trim($row['tipe']);
                        $brand          = trim($row['merek']);
                        $color          = trim($row['warna']);
                        $moq            = trim($row['minimum']);
                        $qty_borrow     = trim($row['pinjaman']);
                        $qty_stock      = trim($row['kuantiti']);
                        $unit_name      = trim($row['satuan']);
                        $dataItem = [
                            'item_old_code' => $item_old_code,
                            'name' => $name,
                            'size' => $size,
                            'tipe' => $tipe,
                            'brand' => $brand,
                            'color' => $color,
                            'qty_borrow' => $qty_borrow,
                            'qty_stock' => $qty_stock,
                            'unit_name' => $unit_name,
                        ];

                        $checkItem = StockOldApp::where('item_old_code', $item_old_code)->first();
                        if (!empty($checkItem)) {
                            $save = StockOldApp::where('item_old_code', $item_old_code)->update($dataItem);
                            $stock_app_old_id = $checkItem->id;
                        } else {
                            if ($qty_stock>0) {
                                $save = StockOldApp::create($dataItem);
                                $stock_app_old_id = $save->id;
                            } else {
                                $stock_app_old_id = 0;
                            }
                        }

                        if ($stock_app_old_id>0) {
                            $checkItem = Item::where('old_code', $item_old_code)->first();
                            if (!empty($checkItem)) {
                                $item_code = $checkItem->code;
                                $conversion = getUnitConversionId($item_code);
                                // B : Stock Transaction
                                if ($qty_stock>0) {
                                    $STOCK_STATUS = StockTransaction::STOCK_AVAILABLE;
                                } else {
                                    $STOCK_STATUS = StockTransaction::STOCK_EMPTY;
                                }

                                $data_stock = [
                                    'item_code' => $item_code,
                                    'quantity' => $qty_stock,
                                    'stock_out' => 0,
                                    'stock_current' => $qty_stock,
                                    'item_unit_conversion_id' => $conversion['id'],
                                    'po_code' => null,
                                    'transaction_symbol'=> "OA",
                                    'transaction_name' => "Stok Akhir App Lama",
                                    'transaction_code' => $stock_app_old_id,
                                    'transaction_date' => date('Y-m-d H:i:s'),
                                    'delivery_order_item_id' => 0,
                                    'entry_status' => StockTransaction::STOCK_IN,
                                    'status' => StockTransaction::STATUS_DONE,
                                    'data_status' => StockTransaction::DATA_OPEN,
                                    'stock_status' => $STOCK_STATUS,
                                ];

                                $checkStockTransaction = StockTransaction::query()
                                    ->where('transaction_code', $stock_app_old_id)
                                    ->where('item_code', $item_code)
                                    ->where('transaction_symbol', "OA")
                                    ->first();
                                if (!empty($checkStockTransaction)) {
                                    StockTransaction::where('id', $checkStockTransaction->id)->update($data_stock);
                                } else {
                                    StockTransaction::create($data_stock);
                                }

                                // B : Update Stock Item
                                stockOldApp::where('id', $stock_app_old_id)->update([
                                    'item_code' => $item_code,
                                ]);

                                // B : Update Stock Item
                                Item::where('old_code', $item_old_code)->update([
                                    'min_stock' => $moq,
                                    'current_stock' => getItemStock($item_code),
                                    'stock_app_old_id' => $stock_app_old_id,
                                ]);
                            }
                        }
                    }
                    DB::commit();
                    return response()->json(['message' => __('Berhasil upload data stock barang.')]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(['message' => $e->getMessage()], 400);
                }
            }
        } else {
            return response()->json(['message' => __('Data barang tidak ditemukan.'), 400]);
        }
         return response()->json(['message' => 'Import item error'], 400);
    }
}
