<?php

use Modules\PurchaseOrder\Entities\PurchaseOrderItems;

use Illuminate\Support\Facades\DB;
use Modules\PurchaseOrder\Entities\OutstandingDiscon;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Entities\StockTransactionOutGoodsValue;

// $setData  = [
//     'item_code' => "",
//     'transaction_symbol' => "",
//     'transaction_name' => "",
//     'transaction_code' => "",
//     'transaction_date' => date('Y-m-d H:i:s'),
//     'out_ic_goods_request_item_id' => "",
//     'out_ic_goods_request_item_out_id' => "",
//     'out_stock_adjustment_item_id' => "",
// ];
// createOutStockGoodsValue($quantity, $unit, $setData);

if (! function_exists('createOutStockGoodsValue')) {
    function createOutStockGoodsValue($quantity, $unit, $data)
    {
        $stockTransaction = StockTransaction::query()
            ->where('item_code', $data['item_code'])
            ->where('stock_status', StockTransaction::STOCK_AVAILABLE)
            ->where('stock_current', '>', 0)
            ->orderBy('transaction_code', 'ASC')
            ->first();

        if (!empty($stockTransaction)) {
            $setQuantity = getStockAvailableGoodsValue($stockTransaction->id, $quantity, $unit, $stockTransaction->quantity, $stockTransaction->item_unit_conversion_id);

            if ($setQuantity['out_quantity']>0) {
                if ($stockTransaction->delivery_order_item_id>0) {
                    $in_purchasing_purchase_order_item_id = (!empty($stockTransaction->deliveryOrderItem)) ? $stockTransaction->deliveryOrderItem->purchasing_purchase_order_item_id : 0;
                } else {
                    $in_purchasing_purchase_order_item_id = 0;
                }

                $goodsValue = [
                    'item_code' => $stockTransaction->item_code,
                    'stock_transaction_id' => $stockTransaction->id,
                    'in_delivery_order_item_id' => $stockTransaction->delivery_order_item_id,
                    'in_purchasing_purchase_order_item_id' => $in_purchasing_purchase_order_item_id,
                    'in_stock_adjustment_item_id' => $stockTransaction->stock_adjustment_item_id,
                    'transaction_symbol' => $data['transaction_symbol'],
                    'transaction_name' => $data['transaction_name'],
                    'transaction_code' => $data['transaction_code'],
                    'transaction_category' => $data['transaction_category'],
                    'transaction_date' => date('Y-m-d H:i:s'),
                    'out_ic_goods_request_item_id' => $data['out_ic_goods_request_item_id'],
                    'out_ic_goods_request_item_out_id' => $data['out_ic_goods_request_item_out_id'],
                    'out_stock_adjustment_item_id' => $data['out_stock_adjustment_item_id'],
                    'out_ic_goods_borrow_item_id' => $data['out_ic_goods_borrow_item_id'],
                    'out_goods_return_code' => $data['out_goods_return_code'],
                    'out_goods_repairment_code' => $data['out_goods_repairment_code'],
                    'out_quantity' => $setQuantity['out_quantity'],
                    'out_item_unit_conversion_id' => $setQuantity['out_item_unit_conversion_id'],
                    'status' => StockTransactionOutGoodsValue::ACTIVE,
                    'borrow_status' => $data['borrow_status'],
                    'repairment_status' => $data['repairment_status'],
                ];

                StockTransactionOutGoodsValue::create($goodsValue);

                $stock_status = ($setQuantity['available_quantity']>0) ? StockTransaction::STOCK_AVAILABLE : StockTransaction::STOCK_EMPTY;
                StockTransaction::where('id', $stockTransaction->id)->update([
                    'stock_status' => $stock_status,
                    'stock_out' => getStockTransaction($stockTransaction->id, 'out'),
                    'stock_current' => getStockTransaction($stockTransaction->id, 'current'),
                ]);
            }

            if ($setQuantity['pending_quantity']>0) {
                return createOutStockGoodsValue($setQuantity['pending_quantity'], $setQuantity['pending_item_unit_conversion_id'], $data);
            }
        } else {
            return "kosong";
        }
    }
}

if (! function_exists('createOutStockGoodsValueRetur')) {
    function createOutStockGoodsValueRetur($quantity, $unit, $data, $delivery_order_item_id)
    {
        $stockTransaction = StockTransaction::query()
            ->where('item_code', $data['item_code'])
            ->where('stock_status', StockTransaction::STOCK_AVAILABLE)
            ->where('delivery_order_item_id', $delivery_order_item_id)
            ->where('stock_current', '>', 0)
            ->orderBy('transaction_code', 'ASC')
            ->first();

        if (!empty($stockTransaction)) {
            $setQuantity = getStockAvailableGoodsValue($stockTransaction->id, $quantity, $unit, $stockTransaction->quantity, $stockTransaction->item_unit_conversion_id);

            if ($setQuantity['out_quantity']>0) {
                if ($stockTransaction->delivery_order_item_id>0) {
                    $in_purchasing_purchase_order_item_id = (!empty($stockTransaction->deliveryOrderItem)) ? $stockTransaction->deliveryOrderItem->purchasing_purchase_order_item_id : 0;
                } else {
                    $in_purchasing_purchase_order_item_id = 0;
                }

                $goodsValue = [
                    'item_code' => $stockTransaction->item_code,
                    'stock_transaction_id' => $stockTransaction->id,
                    'in_delivery_order_item_id' => $stockTransaction->delivery_order_item_id,
                    'in_purchasing_purchase_order_item_id' => $in_purchasing_purchase_order_item_id,
                    'in_stock_adjustment_item_id' => $stockTransaction->stock_adjustment_item_id,
                    'transaction_symbol' => $data['transaction_symbol'],
                    'transaction_name' => $data['transaction_name'],
                    'transaction_code' => $data['transaction_code'],
                    'transaction_category' => $data['transaction_category'],
                    'transaction_date' => date('Y-m-d H:i:s'),
                    'out_ic_goods_request_item_id' => $data['out_ic_goods_request_item_id'],
                    'out_ic_goods_request_item_out_id' => $data['out_ic_goods_request_item_out_id'],
                    'out_stock_adjustment_item_id' => $data['out_stock_adjustment_item_id'],
                    'out_ic_goods_borrow_item_id' => $data['out_ic_goods_borrow_item_id'],
                    'out_goods_return_code' => $data['out_goods_return_code'],
                    'out_quantity' => $setQuantity['out_quantity'],
                    'out_item_unit_conversion_id' => $setQuantity['out_item_unit_conversion_id'],
                    'status' => StockTransactionOutGoodsValue::ACTIVE,
                    'borrow_status' => $data['borrow_status'],
                ];

                StockTransactionOutGoodsValue::create($goodsValue);

                $stock_status = ($setQuantity['available_quantity']>0) ? StockTransaction::STOCK_AVAILABLE : StockTransaction::STOCK_EMPTY;
                StockTransaction::where('id', $stockTransaction->id)->update([
                    'stock_status' => $stock_status,
                    'stock_out' => getStockTransaction($stockTransaction->id, 'out'),
                    'stock_current' => getStockTransaction($stockTransaction->id, 'current'),
                ]);
            }

            if ($setQuantity['pending_quantity']>0) {
                return createOutStockGoodsValueRetur($setQuantity['pending_quantity'], $setQuantity['pending_item_unit_conversion_id'], $data, $delivery_order_item_id);
            }
        } else {
            return "kosong";
        }
    }
}


if (! function_exists('cancelOutStockGoodsValue')) {
    function cancelOutStockGoodsValue($quantity_cancel, $unit, $data)
    {
        $quantiti_tmp = 0;
        $goodsValue = StockTransactionOutGoodsValue::query()
            ->where('out_ic_goods_request_item_out_id', $data['ic_goods_request_item_out_id'])
            ->where('status', StockTransactionOutGoodsValue::ACTIVE)
            ->orderBy('id', 'DESC')
            ->get();

        if (!empty($goodsValue)) {
            foreach ($goodsValue as $gv) {
                $stock_transaction_id = $gv['stock_transaction_id'];
                $quantity_value       = $gv['out_quantity'];
                if ($quantity_value>=$quantity_cancel) {
                    $quantiti_tmp = $quantity_cancel;
                    $quantity_value = 0;
                } else {
                    $quantiti_tmp = $quantity_value;
                    $quantity_cancel = $quantity_cancel - $quantity_value;
                }

                $data_cancel = ['cancel_quantity' => $quantiti_tmp,
                         'cancel_item_unit_conversion_id' => $unit,
                         'cancel_ic_goods_request_item_return_id' => $data['ic_goods_request_item_return_id'],
                        ];

                StockTransactionOutGoodsValue::where('id', $gv['id'])->update($data_cancel);

                if ($quantity_cancel<=0) {
                    break;
                    //return "ok";
                } else {
                     StockTransaction::where('id', $stock_transaction_id)->update([
                            'stock_status' => StockTransaction::STOCK_AVAILABLE,
                            'stock_out' => getStockTransaction($stock_transaction_id, 'out'),
                            'stock_current' => getStockTransaction($stock_transaction_id, 'current'),
                ]);
                }
            }
        } else {
            return "empty";
        }
    }
}

if (! function_exists('getStockAvailableGoodsValue')) {
    function getStockAvailableGoodsValue($stock_transaction_id, $quantity, $unit, $stock, $stock_units)
    {
        $checkStockOut = StockTransactionOutGoodsValue::query()
            ->select(DB::raw('sum(out_quantity) as total'))
            ->where('stock_transaction_id', $stock_transaction_id)
            ->where('status', StockTransactionOutGoodsValue::ACTIVE)
            ->first();
        $total_stock_out = (!empty($checkStockOut)) ? ($checkStockOut->total) : 0;

        $checkStockCancel = StockTransactionOutGoodsValue::query()
            ->select(DB::raw('sum(cancel_quantity) as total'))
            ->where('stock_transaction_id', $stock_transaction_id)
            ->where('status', StockTransactionOutGoodsValue::ACTIVE)
            ->first();
        $total_stock_cancel = (!empty($checkStockCancel)) ? ($checkStockCancel->total) : 0;

        $total_out   = $total_stock_out-$total_stock_cancel;
        if ($total_out<=0) {
            $total_out = 0;
        }

        $total_stock = (!empty($total_out)) ? ($stock-$total_out) : $stock;

        if ($total_stock>=$quantity) {
            $out_quantity                       = $quantity;
            $out_item_unit_conversion_id        = $unit;
            $pending_quantity                   = 0;
            $pending_item_unit_conversion_id    = $unit;
            $available_quantity                 = $total_stock - $quantity;
            $available_item_unit_conversion_id  = $unit;
        } else {
            $out_quantity                       = $total_stock;
            $out_item_unit_conversion_id        = $unit;
            $pending_quantity                   = $quantity - $total_stock;
            $pending_item_unit_conversion_id    = $unit;
            $available_quantity                 = 0;
            $available_item_unit_conversion_id  = $unit;
        }

        $data = [
            'out_quantity' => $out_quantity,
            'out_item_unit_conversion_id' => $out_item_unit_conversion_id,
            'pending_quantity' => $pending_quantity,
            'pending_item_unit_conversion_id' => $pending_item_unit_conversion_id,
            'available_quantity' => $available_quantity,
            'available_item_unit_conversion_id' => $available_item_unit_conversion_id,
        ];

        return $data;
    }
}

if (! function_exists('getGoodsValue')) {
    function getGoodsValue($id)
    {

        $data = PurchaseOrderItems::with('purchaseOrder')
            ->where('id',$id)
            ->first();

        if (!empty($data)) {
            $discon   = getGoodsValueDiscon($data->id);
            $po_price = ($data->price-$discon);
        } else {
            $po_price = 0;
        }

        if (!empty($data->purchaseOrder)) {
            $exchange_rate = ($data->purchaseOrder->exchange_rate>0) ? $data->purchaseOrder->exchange_rate : 1;
        } else {
            $exchange_rate = 1;
        }

        $price         = ($po_price * $exchange_rate);
        return !empty($data) ? $price : 0;
    }
}

if (! function_exists('getGoodsValueDiscon')) {
    function getGoodsValueDiscon($purchase_order_item_id)
    {
        $data = OutstandingDiscon::with('purchaseOrderItems')
            ->where('purchase_order_item_id',$purchase_order_item_id)
            ->first();
        if (!empty($data)) {
            if (!empty($data->purchaseOrderItems)) {
                $qty = ($data->purchaseOrderItems->quantity>0) ? $data->purchaseOrderItems->quantity : 1;
            } else {
                $qty = 1;
            }

            $discon = ($data->discon_nominal/$qty);
        } else {
            $discon = 0;
        }

        return !empty($data) ? $discon : 0;
    }
}
