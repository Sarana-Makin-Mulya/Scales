<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\PublicWarehouse\Entities\DeliveryOrderItem;
use Modules\PublicWarehouse\Entities\GoodsRequest;
use Modules\PublicWarehouse\Entities\GoodsReturn;
use Modules\PublicWarehouse\Entities\GoodsReturnCategory;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\ItemUnitConversion;
use Modules\Stock\Entities\StockClosing;
use Modules\Stock\Entities\StockOpname;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Entities\StockTransactionOutGoodsValue;

if (! function_exists('getFirstStock')) {
    function getFirstStock($month, $year, $type)
    {
        $current_month = $month;
        $current_year  = $year;
        $current_type  = $type;

        // if ($type=="CS") {
        //     // $last_month    = ($current_month <= 2) ? 12-(2-$current_month) : ($current_month-2);
        //     // $last_year     = ($current_month <= 2) ? ($current_year-1) : $current_year;
        //     $last_month    = ($current_month ==1) ? 12 : ($current_month-1);
        //     $last_year     = ($current_month ==1) ? ($current_year-1) : $current_year;
        // } else {
            $last_month    = ($current_month ==1) ? 12 : ($current_month-1);
            $last_year     = ($current_month ==1) ? ($current_year-1) : $current_year;
        //}


        $lastStockCLosing = StockClosing::query()
            ->whereYear('issue_date', '=', $last_year)
            ->whereMonth('issue_date', '=', $last_month)
            ->where('status', 1)
            ->orderBy('created_at', 'DESC')
            ->first();

        return (!empty($lastStockCLosing)) ? $lastStockCLosing->id : null;
    }
}

if (! function_exists('getItemTransaction')) {
    function getItemTransaction($closing_month, $closing_year)
    {
        $arr = [];
        $stock_closing_id = getFirstStock($closing_month, $closing_year , 'CS');
        if ($stock_closing_id>0) {
            $data = StockTransaction::query()
                ->where('entry_status', StockTransaction::STOCK_SUMMARY)
                ->where('stock_closing_id', $stock_closing_id)
                ->where('status',StockTransaction::STATUS_DONE)
                ->groupBy('item_code')
                ->get();

            if ($data->count()>0) {
                foreach ($data as $dt) {
                    $arr[] = $dt->item_code;
                }
            }
        }

        $data = StockTransaction::query()
            ->whereYear('created_at', '=', $closing_year)
            ->whereMonth('created_at', '=', $closing_month)
            ->where('status',StockTransaction::STATUS_DONE)
            ->groupBy('item_code')
            ->get();

        if ($data->count()>0) {
            foreach ($data as $dt) {
                $arr[] = $dt->item_code;
            }
        }

        return $arr;
    }
}

if (! function_exists('getClosingStockStatus')) {
    function getClosingStockStatus()
    {
        $current_month = date('m');
        $current_year  = date('Y');

        $lastclosingStock  = StockClosing::query()
            ->where('status', 1)
            ->orderBy('issue_date', 'desc')
            ->first();

        if (!empty($lastclosingStock)) {
            $get_last_month = (int) substr($lastclosingStock->issue_date, 5, 2);
            $get_last_year  = (int) substr($lastclosingStock->issue_date, 0, 4);

            $last_month    = ($get_last_month == 12) ? 1 : $get_last_month+1;
            $last_year     = ($get_last_month == 12) ? ($get_last_year+1) : $get_last_year;


            if ($last_month>=$current_month && $last_year>=$current_year) {
                $last_month    = ($current_month == 1) ? 12 : ($current_month-1);
                $last_year     = ($current_month == 1) ? ($current_year-1) : $current_year;
            }
        } else {
            $last_month    = ($current_month == 1) ? 12 : ($current_month-1);
            $last_year     = ($current_month == 1) ? ($current_year-1) : $current_year;
        }

        // $last_month    = ($current_month == 1) ? 12 : ($current_month -1);
        // $last_year     = ($current_month == 1) ? ($current_year-1) : $current_year;

        $closingStock  = StockClosing::query()
            ->whereMonth('issue_date', $last_month)
            ->whereYear('issue_date', $last_year)
            ->where('status', 1)
            ->first();

        if (!empty($closingStock)) {
            // $max_date = Carbon::now()->daysInMonth;
            // if ($current_date>=$max_date) {
            //     $data['status']     = 0;
            //     $data['month']      = abs($current_month);
            //     $data['monthName']  = getMonthName(abs($current_month));
            //     $data['year']       = abs($current_year);
            // } else {
                $data['status']     = 1;
                $data['month']      = abs(substr($closingStock->issue_date,5,2));
                $data['monthName']  = getMonthName(abs(substr($closingStock->issue_date,5,2)));
                $data['year']       = abs(substr($closingStock->issue_date,0,4));
            // }
        } else {
            $data['status']     = 0;
            $data['month']      = abs($last_month);
            $data['monthName']  = getMonthName(abs($last_month));
            $data['year']       = abs($last_year);
        }
        return $data;
    }
}

if (! function_exists('getClosingStockStatusTest')) {
    function getClosingStockStatusTest()
    {
        $current_month = date('m');
        $current_year  = date('Y');

        $lastclosingStock  = StockClosing::query()
            ->where('status', 1)
            ->orderBy('issue_date', 'desc')
            ->first();

        if (!empty($lastclosingStock)) {
            $get_last_month = (int) substr($lastclosingStock->issue_date, 5, 2);
            $get_last_year  = (int) substr($lastclosingStock->issue_date, 0, 4);
            $last_month    = ($get_last_month == 12) ? 1 : $get_last_month+1;
            $last_year     = ($get_last_month == 12) ? ($current_year+1) : $get_last_year;
        } else {
            $last_month    = ($current_month == 1) ? 12 : ($current_month-1);
            $last_year     = ($current_month == 1) ? ($current_year-1) : $current_year;
        }

        $closingStock  = StockClosing::query()
            ->whereMonth('issue_date', $last_month)
            ->whereYear('issue_date', $last_year)
            ->where('status', 1)
            ->first();

        if (!empty($closingStock)) {
            $data['status']     = 1;
            $data['month']      = abs(substr($closingStock->issue_date,5,2));
            $data['monthName']  = getMonthName(abs(substr($closingStock->issue_date,5,2)));
            $data['year']       = abs(substr($closingStock->issue_date,0,4));
        } else {
            $data['status']     = 0;
            $data['month']      = abs($last_month);
            $data['monthName']  = getMonthName(abs($last_month));
            $data['year']       = abs($last_year);
        }
        return $data;
    }
}

if (! function_exists('getStockTransaction')) {
    function getStockTransaction($id, $type)
    {
        $stockTransaction = StockTransaction::query()
            ->select('quantity')
            ->where('id', $id)
            ->first();

        if (!empty($stockTransaction)) {
            $quantity_in = $stockTransaction->quantity;

            $outQuantity = StockTransactionOutGoodsValue::query()
                ->select(DB::raw('sum(out_quantity) as total_out_quantity'))
                ->where('stock_transaction_id', $id)
                ->where('status', StockTransactionOutGoodsValue::ACTIVE)
                ->first();
            $total_out_quantity = ($outQuantity->total_out_quantity>0) ? $outQuantity->total_out_quantity : 0;

            $cancelQuantity = StockTransactionOutGoodsValue::query()
                ->select(DB::raw('sum(cancel_quantity) as total_cancel_quantity'))
                ->where('stock_transaction_id', $id)
                ->where('status', StockTransactionOutGoodsValue::ACTIVE)
                ->first();

            $total_cancel_quantity = ($cancelQuantity->total_cancel_quantity>0) ? $cancelQuantity->total_cancel_quantity : 0;

            $quantity_out = $total_out_quantity - $total_cancel_quantity;
            $quantity_out = ($quantity_out<0) ? 0 : $quantity_out;
            $quantity     = $quantity_in - $quantity_out;
            $quantity     = ($quantity<0) ? 0 : $quantity;

            if ($type=="out") {
                return $quantity_out;
            } else {
                return $quantity;
            }
        } else {
            return 0;
        }
    }
}


if (! function_exists('getQuotaReturn')) {
    function getQuotaReturn($deliveryOrderItemId, $goodsReturnCode, $status)
    {
        $stockTransaction = StockTransaction::query()
            ->where('delivery_order_item_id', $deliveryOrderItemId)
            ->where('transaction_symbol', 'DO')
            ->first();

        $goodsReturnCode = (!empty($goodsReturnCode)) ? $goodsReturnCode : null;
        if ($status=="edit") {
            $GoodsReturn = GoodsReturn::query()
                ->where('code', $goodsReturnCode)
                ->first();
            $total_return_edit = (!empty($GoodsReturn)) ? $GoodsReturn->quantity : 0;
        } else {
            $total_return_edit = 0;
        }

        if (!empty($stockTransaction)) {
            $quota  = $stockTransaction->stock_current + $total_return_edit;
            return ($quota<0) ? 0 : $quota;
        } else {
            return 0;
        }
    }
}

if (! function_exists('getTotalReturn')) {
    function getTotalReturn($deliveryOrderItemId, $goodsReturnCode, $status)
    {
        $query = GoodsReturn::query();

        if ($status=="edit") {
            $query = $query->where('code', '<>', $goodsReturnCode);
        }

        $GoodsReturn = $query
            ->select(DB::raw('sum(quantity) as total'))
            ->where('delivery_order_item_id', $deliveryOrderItemId)
            ->first();
        if (!empty($GoodsReturn->total)) {
            return $GoodsReturn->total;
        } else {
            return 0;
        }
    }
}

if (! function_exists('getReturnStatus')) {
    function getReturnStatus($return_type, $delivery_type, $process_status)
    {
        if ($process_status==1) {
            if (getReturnCategoryProcess($return_type)==1) {
                if ($delivery_type==6) {
                    //Delivery via expedition
                    $status = GoodsReturn::PROCESS;
                } elseif ($delivery_type==7) {
                    //Direct Return
                    $status = GoodsReturn::PROCESS;
                } else {
                    //Pickup Supplier
                    $status = GoodsReturn::PROCESS;
                }
            } else {
                if ($delivery_type==6) {
                    //Delivery via expedition
                    $status = GoodsReturn::COMPLETED;
                } elseif ($delivery_type==7) {
                    //Direct Return
                    $status = GoodsReturn::COMPLETED;
                } else {
                    //Pickup Supplier
                    $status = GoodsReturn::COMPLETED;
                }
            }
        } else {
            $status = GoodsReturn::WAITING;
        }

        return $status;
    }
}

if (! function_exists('getStockOpname')) {
    function getStockOpname($id, $type)
    {

        $query = StockOpname::query();

        if ($type=="issue") {
            $query = $query->whereIn('stockopname_type', [StockOpname::STOCK_MIN, StockOpname::STOCK_PLUS]);
        } elseif ($type=="balance") {
            $query = $query->where('stockopname_type', StockOpname::STOCK_BALANCE);
        } else {
            $query = $query;
        }

        $stockopname = $query
            ->where('stock_opname_group_id', $id)
            ->get();
        if ($stockopname->count()>0) {
            return $stockopname->count();
        } else {
            return 0;
        }
    }
}

if (! function_exists('getReturnCategoryProcess')) {
    function getReturnCategoryProcess($id)
    {
        $returnCategory = GoodsReturnCategory::query()
            ->where('id', $id)
            ->first();
        if (!empty($returnCategory)) {
            return $returnCategory->process;
        } else {
            return 0;
        }
    }
}

if (! function_exists('getReturnCategory')) {
    function getReturnCategory($id)
    {
        $returnCategory = GoodsReturnCategory::query()
            ->where('id', $id)
            ->first();
        if (!empty($returnCategory)) {
            return $returnCategory->name;
        } else {
            return 0;
        }
    }
}

if (! function_exists('getTransactionCode')) {
    function getTransactionCode($id, $transaction)
    {
        $code = null;
        switch ($transaction) {
            case "goods_request":
                $GoodsRequestItems = new Modules\PublicWarehouse\Entities\GoodsRequestItems;
                $data = $GoodsRequestItems::where('id',$id)->first();
                $code = (!empty($data)) ? $data->ic_goods_request_code : null;
                break;
            case "goods_borrow":
                $GoodsBorrowItem = new Modules\PublicWarehouse\Entities\GoodsBorrowItem;
                $data = $GoodsBorrowItem::where('id',$id)->first();
                $code = (!empty($data)) ? $data->goods_borrow_code : "goods_borrow";
                break;
            case "stock_adjustment":
                $StockAdjustmentItem = new Modules\Stock\Entities\StockAdjustmentItem;
                $data = $StockAdjustmentItem::where('id',$id)->first();
                $code = (!empty($data)) ? $data->stock_adjustment_code : "stock_adjustment";
                break;
            case "delivery_order":
                $DeliveryOrderItem = new Modules\PublicWarehouse\Entities\DeliveryOrderItem;
                $data = $DeliveryOrderItem::where('id',$id)->first();
                $code = (!empty($data)) ? $data->delivery_order_code : "delivery_order";
                break;
            case "goods_return":
                $code = (!empty($id)) ? $id : null;
                break;
            default:
                $code = "-";
                break;
        }
        return $code;
    }
}

if (! function_exists('getItemStockBorrowed')) {
    function getItemStockBorrowed($item_code)
    {
        $model = new Modules\PublicWarehouse\Entities\GoodsBorrowItem;
        $goodsBorrowItem = $model::query()
            ->select(DB::raw('sum(quantity) as total'))
            ->where('item_code', $item_code)
            ->where('return_status', 0)
            ->where('is_active', 1)
            ->first();
        return (!empty($goodsBorrowItem->total)) ? $goodsBorrowItem->total : 0;
    }
}

if (! function_exists('getUnitConversionId')) {
    function getUnitConversionId($item_code)
    {
        $ItemUnitConversion = ItemUnitConversion::query()
            ->where('item_code', $item_code)
            ->where('is_active', 1)
            ->where('is_primary', 1)
            ->first();
        if (!empty($ItemUnitConversion)) {
            return ["id" => $ItemUnitConversion->id, "conversion_symbol" => getUnitName($ItemUnitConversion->unit_id)];
        } else {
            return ["id" => 0, "conversion_symbol" => "-"];
        }
    }
}

if (! function_exists('getItemStock')) {
    function getItemStock($item_code)
    {
        $stock_first = getItemStockTransaction($item_code, 0);
        $stock_in = getItemStockTransaction($item_code, 1);
        $stock_out = getItemStockTransaction($item_code, 2);
        $borrowed = getItemStockBorrowed($item_code);
        $stock_total = ($stock_first + $stock_in) - ($stock_out + $borrowed);
        return (!empty($stock_total)) ? $stock_total : 0;
    }
}

// if (! function_exists('getItemStockReturn')) {
    //     function getItemStockReturn($item_code)
    //     {
    //         $stock_first = getItemStockTransactionReturn($item_code, 0);
    //         $stock_in = getItemStockTransactionReturn($item_code, 1);
    //         $stock_out = getItemStockTransactionReturn($item_code, 2);
    //         $borrowed = getItemStockBorrowed($item_code);
    //         $stock_total = ($stock_first + $stock_in) - ($stock_out + $borrowed);
    //         return (!empty($stock_total)) ? $stock_total : 0;
    //     }
// }

if (! function_exists('getItemStockString')) {
    function getItemStockString($item_code)
    {
        $stock_first = getItemStockTransaction($item_code, 0);
        $stock_in = getItemStockTransaction($item_code, 1);
        $stock_out = getItemStockTransaction($item_code, 2);
        $stock_total = ($stock_first + $stock_in) - $stock_out;
        return (!empty($stock_total)) ? $stock_total : 0;
    }
}

if (! function_exists('getItemStockClosingTest')) {
    function getItemStockClosingTest($item_code, $month, $year)
    {
        $stock_first = getItemStockTransactionClosing($item_code, $month, $year, 0);
        $stock_in = getItemStockTransactionClosing($item_code, $month, $year, 1);
        $stock_out = getItemStockTransactionClosing($item_code, $month, $year, 2);
        $stock_total = ($stock_first + $stock_in) - $stock_out;

        return ['code' => $item_code, 'name' => getItemDetail($item_code), 'stock_first' => $stock_first, 'stock_in' => $stock_in, 'stock_out' => $stock_out, 'stock_total' => $stock_total];
        //return (!empty($stock_total)) ? $stock_total : 0;
    }
}

if (! function_exists('getItemStockClosing')) {
    function getItemStockClosing($item_code, $month, $year)
    {
        $stock_first = getItemStockTransactionClosing($item_code, $month, $year, 0);
        $stock_in = getItemStockTransactionClosing($item_code, $month, $year, 1);
        $stock_out = getItemStockTransactionClosing($item_code, $month, $year, 2);
        $stock_total = ($stock_first + $stock_in) - $stock_out;

        return (!empty($stock_total)) ? $stock_total : 0;
    }
}

if (! function_exists('getTotalItemOut')) {
    function getTotalItemOut($item_code, $month, $year)
    {
        $stock = StockTransaction::query()
            ->select(DB::raw('sum(quantity) as total'))
            ->where('item_code', $item_code)
            ->where('status', 1)
            ->where('entry_status', 2)
            ->where('transaction_symbol', 'GR')
            ->where('ic_goods_request_item_id', '>', 0)
            ->where('ic_goods_request_item_out_id', '>', 0)
            ->whereRaw('MONTH(transaction_date) = '.$month)
            ->whereRaw('YEAR(transaction_date) = '.$year)
            ->first();
        $quantity_stock = (!empty($stock->total)) ? $stock->total : 0;
        return $quantity_stock;
    }
}

if (! function_exists('getItemStockTransaction')) {
    function getItemStockTransaction($item_code, $category)
    {
        $quantity_stock = 0;
        if ($category==0) {
            // Stock Awal
            $stock_closing_id = getFirstStock(date('m'), date('Y'), 'stock');
            if ($stock_closing_id>0) {
                $stock = StockTransaction::query()
                    ->where('item_code', $item_code)
                    ->where('status', 1)
                    ->where('entry_status', 0)
                    ->where('transaction_symbol', 'CS')
                    ->where('stock_closing_id', $stock_closing_id)
                    ->first();
                $quantity_stock = (!empty($stock)) ? $stock->quantity : 0;
            } else {
                $quantity_stock = 0;
            }
        } elseif ($category==1) {
            // Barang masuk selain dari delivery order
            $stock_as = StockTransaction::query()
                ->select(DB::raw('sum(quantity) as total'))
                ->where('item_code', $item_code)
                ->where('status', 1)
                ->where('entry_status', 1)
                ->where('stock_closing_id', 0)
                ->where('delivery_order_item_id',0)
                ->where('ic_goods_borrow_item_id', 0)
                ->whereRaw('MONTH(transaction_date) = '.date('m'))
                ->whereRaw('YEAR(transaction_date) = '.date('Y'))
                ->first();
            $quantity_stock_as = (!empty($stock_as->total)) ? $stock_as->total : 0;

            // Barang masuk dari delivery order dan sudah di simpan di rack
            $stock_do = StockTransaction::query()
                ->select(DB::raw('sum(quantity) as total'))
                ->whereHas('deliveryOrderItem', function ($query) {
                    $query->where('storaged',1);
                })
                ->where('item_code', $item_code)
                ->where('status', 1)
                ->where('entry_status', 1)
                ->where('stock_closing_id', 0)
                ->where('delivery_order_item_id','>',0)
                ->where('ic_goods_borrow_item_id', 0)
                ->whereRaw('MONTH(transaction_date) = '.date('m'))
                ->whereRaw('YEAR(transaction_date) = '.date('Y'))
                ->first();

            $quantity_stock_do = (!empty($stock_do->total)) ? $stock_do->total : 0;
            $quantity_stock    = $quantity_stock_as + $quantity_stock_do;
        } elseif ($category==3) {
            // Barang masuk dari delivery order dan belum di simpan di rack
            $stock_quarantine = StockTransaction::query()
                ->select(DB::raw('sum(quantity) as total'))
                ->whereHas('deliveryOrderItem', function ($query) {
                    $query->where('storaged',0);
                })
                ->where('item_code', $item_code)
                ->where('status', 1)
                ->where('entry_status', 1)
                ->where('stock_closing_id', 0)
                ->where('delivery_order_item_id','>',0)
                ->where('ic_goods_borrow_item_id', 0)
                ->whereRaw('MONTH(transaction_date) = '.date('m'))
                ->whereRaw('YEAR(transaction_date) = '.date('Y'))
                ->first();

            $quantity_stock = (!empty($stock_quarantine->total)) ? $stock_quarantine->total : 0;
        } else {
            $stock = StockTransaction::query()
                ->select(DB::raw('sum(quantity) as total'))
                ->where('item_code', $item_code)
                ->where('status', 1)
                ->where('entry_status', 2)
                ->where('stock_closing_id', 0)
                ->where('ic_goods_borrow_item_id', 0)
                ->whereRaw('MONTH(transaction_date) = '.date('m'))
                ->whereRaw('YEAR(transaction_date) = '.date('Y'))
                ->first();
            $quantity_stock = (!empty($stock->total)) ? $stock->total : 0;
        }

        return $quantity_stock;
    }
}

// if (! function_exists('getItemStockTransactionReturn')) {
    //     function getItemStockTransactionReturn($item_code, $category)
    //     {
    //         $quantity_stock = 0;
    //         $model = new Modules\Stock\Entities\StockTransaction;

    //         if ($category==0) {
    //             // Stock Awal
    //             // $stock = $model::query()
    //             //     ->where('item_code', $item_code)
    //             //     ->where('status', 1)
    //             //     ->where('entry_status', 0)
    //             //     ->whereRaw('MONTH(transaction_date) = '.date('m'))
    //             //     ->whereRaw('YEAR(transaction_date) = '.date('Y'))
    //             //     ->first();
    //             // $quantity_stock = (!empty($stock)) ? $stock->quantity : 0;

    //             $stock_closing_id = getFirstStock(date('m'), date('Y'), 'stock');
    //             if ($stock_closing_id>0) {
    //                 $stock = $model::query()
    //                     ->where('item_code', $item_code)
    //                     ->where('status', 1)
    //                     ->where('entry_status', 0)
    //                     ->where('transaction_symbol', 'CS')
    //                     ->where('stock_closing_id', $stock_closing_id)
    //                     ->first();
    //                 $quantity_stock = (!empty($stock)) ? $stock->quantity : 0;
    //             } else {
    //                 $quantity_stock = 0;
    //             }

    //         } elseif ($category==1) {
    //             // Stock Masuk
    //             $stock = $model::query()
    //                 ->select(DB::raw('sum(quantity) as total'))
    //                 ->where('item_code', $item_code)
    //                 ->where('status', 1)
    //                 ->where('entry_status', 1)
    //                 ->where('stock_closing_id', 0)
    //                 ->where('ic_goods_borrow_item_id', 0)
    //                 ->whereRaw('MONTH(transaction_date) = '.date('m'))
    //                 ->whereRaw('YEAR(transaction_date) = '.date('Y'))
    //                 ->first();
    //             $quantity_stock = (!empty($stock)) ? $stock->total : 0;
    //         } else {
    //             // Stock Keluar
    //             $stock = $model::query()
    //                 ->select(DB::raw('sum(quantity) as total'))
    //                 ->where('item_code', $item_code)
    //                 ->where('status', 1)
    //                 ->where('entry_status', 2)
    //                 ->where('stock_closing_id', 0)
    //                 ->where('ic_goods_borrow_item_id', 0)
    //                 ->whereRaw('MONTH(transaction_date) = '.date('m'))
    //                 ->whereRaw('YEAR(transaction_date) = '.date('Y'))
    //                 ->first();
    //             $quantity_stock = (!empty($stock->total)) ? $stock->total : 0;
    //         }

    //         return $quantity_stock;
    //     }
// }

if (! function_exists('getItemStockTransactionClosing')) {
    function getItemStockTransactionClosing($item_code, $month, $year, $category)
    {
        $quantity_stock = 0;
        $model = new Modules\Stock\Entities\StockTransaction;

        if ($category==0) {
            // Stock Awal
            // $stock = $model::query()
            //     ->where('item_code', $item_code)
            //     ->where('status', 1)
            //     ->where('entry_status', 0)
            //     ->whereRaw('MONTH(transaction_date) = '.$month)
            //     ->whereRaw('YEAR(transaction_date) = '.$year)
            //     ->first();
            // $quantity_stock = (!empty($stock)) ? $stock->quantity : 0;

            $stock_closing_id = getFirstStock($month, $year, 'stock');
            if ($stock_closing_id>0) {
                $stock = $model::query()
                    ->where('item_code', $item_code)
                    ->where('status', 1)
                    ->where('entry_status', 0)
                    ->where('transaction_symbol', 'CS')
                    ->where('stock_closing_id', $stock_closing_id)
                    ->first();
                $quantity_stock = (!empty($stock)) ? $stock->quantity : 0;
            } else {
                $quantity_stock = 0;
            }
        } elseif ($category==1) {
            // $stock = $model::query()
            //     ->select(DB::raw('sum(quantity) as total'))
            //     ->where('item_code', $item_code)
            //     ->where('status', 1)
            //     ->where('entry_status', 1)
            //     ->whereRaw('MONTH(transaction_date) = '.$month)
            //     ->whereRaw('YEAR(transaction_date) = '.$year)
            //     ->first();
            // $quantity_stock = (!empty($stock->total)) ? $stock->total : 0;

            // Barang masuk selain dari delivery order
            $stock_as = $model::query()
                ->select(DB::raw('sum(quantity) as total'))
                ->where('item_code', $item_code)
                ->where('status', 1)
                ->where('entry_status', 1)
                ->where('stock_closing_id', 0)
                ->where('delivery_order_item_id',0)
                ->where('ic_goods_borrow_item_id', 0)
                ->whereRaw('MONTH(transaction_date) = '.$month)
                ->whereRaw('YEAR(transaction_date) = '.$year)
                ->first();
            $quantity_stock_as = (!empty($stock_as->total)) ? $stock_as->total : 0;

            // Barang masuk dari delivery order dan sudah di simpan di rack
            $stock_do = $model::query()
                ->select(DB::raw('sum(quantity) as total'))
                ->whereHas('deliveryOrderItem', function ($query) {
                    $query->where('storaged',1);
                })
                ->where('item_code', $item_code)
                ->where('status', 1)
                ->where('entry_status', 1)
                ->where('stock_closing_id', 0)
                ->where('delivery_order_item_id','>',0)
                ->where('ic_goods_borrow_item_id', 0)
                ->whereRaw('MONTH(transaction_date) = '.$month)
                ->whereRaw('YEAR(transaction_date) = '.$year)
                ->first();

            $quantity_stock_do = (!empty($stock_do->total)) ? $stock_do->total : 0;
            $quantity_stock    = $quantity_stock_as + $quantity_stock_do;
        } else {
            // $stock = $model::query()
            //     ->select(DB::raw('sum(quantity) as total'))
            //     ->where('item_code', $item_code)
            //     ->where('status', 1)
            //     ->where('entry_status', 2)
            //     ->whereRaw('MONTH(transaction_date) = '.$month)
            //     ->whereRaw('YEAR(transaction_date) = '.$year)
            //     ->first();
            // $quantity_stock = (!empty($stock->total)) ? $stock->total : 0;

            $stock = $model::query()
                ->select(DB::raw('sum(quantity) as total'))
                ->where('item_code', $item_code)
                ->where('status', 1)
                ->where('entry_status', 2)
                ->where('stock_closing_id', 0)
                ->where('ic_goods_borrow_item_id', 0)
                ->whereRaw('MONTH(transaction_date) = '.$month)
                ->whereRaw('YEAR(transaction_date) = '.$year)
                ->first();
            $quantity_stock = (!empty($stock->total)) ? $stock->total : 0;
        }

        return $quantity_stock;
    }
}

if (! function_exists('getQuantityConversionAll')) {
    function getQuantityConversionAll($item_code, $quantity)
    {
        $conversion = [];
        $items = ItemUnitConversion:: with('unit')
            ->where('is_active', 1)
            ->where('item_code', $item_code)
            ->orderBy('is_primary', 'Desc')
            ->get();

        foreach ($items as $item) {
            $conversion[] = getQuantityConversion($item_code, $quantity, $item['id']);
        }
        return $conversion;
    }
}

if (! function_exists('getQuantityConversion')) {
    function getQuantityConversion($item_code, $quantity, $unit_conversion_id)
    {
        $item = ItemUnitConversion:: with('unit')
            ->where('is_active', 1)
            ->where('item_code', $item_code)
            ->where('id', $unit_conversion_id)
            ->first();

        $conversionMax  = ItemUnitConversion:: with('unit')
            ->where('is_active', 1)
            ->where('item_code', $item_code)
            ->orderBy('conversion_value', 'Desc')
            ->first();

        $conversionMin  = ItemUnitConversion:: with('unit')
            ->where('is_active', 1)
            ->where('item_code', $item_code)
            ->orderBy('conversion_value', 'Asc')
            ->first();


        if ($item['conversion_value']==$conversionMin['conversion_value']) {
            $hasil = ($item['conversion_value'] * $conversionMax['conversion_value']) * $quantity;
            $conversion = $hasil;
        } elseif ($item['conversion_value']==$conversionMax['conversion_value']) {
            $hasil = $quantity;
            $conversion = $hasil;
        } else {
            $hasil = ($quantity/$item['conversion_value']) * $conversionMax['conversion_value'];
            $conversion = $hasil;
        }


        return $conversion;
    }
}

