<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PublicWarehouse\Entities\DeliveryOrderItem;
use Modules\PublicWarehouse\Entities\GoodsBorrowItem;
use Modules\PublicWarehouse\Entities\GoodsReleaseItem;
use Modules\PublicWarehouse\Entities\GoodsRepairment;
use Modules\PublicWarehouse\Entities\GoodsRequestItemOut;
use Modules\PublicWarehouse\Entities\GoodsRequestItems;
use Modules\PublicWarehouse\Entities\GoodsReturn;
use Modules\PurchaseOrder\Entities\PurchaseOrderItems;

class StockTransactionOutGoodsValue extends Model
{
    use SoftDeletes;
    protected $table    = "stock_transaction_out_goods_values";

    // Status
    const ACTIVE        = 1;
    const CANCEL        = 0;

    // Data Status
    const DATA_OPEN     = 0;
    const DATA_LOCK     = 1;

    // Category
    const GOODS_VALUE     = 1;
    const PENALTY_VALUE   = 2;
    const RETURN          = 3;
    const BORROW          = 4;
    const REPAIRMENT      = 5;

    const BORROW_NOT      = 0;
    const BORROW_PROCESS  = 1;
    const BORROW_DONE     = 2;

    const REPAIRMENT_NOT      = 0;
    const REPAIRMENT_PROCESS  = 1;
    const REPAIRMENT_DONE     = 2;

    protected $fillable = [
        'item_code',
        'stock_transaction_id',
        'in_delivery_order_item_id',
        'in_purchasing_purchase_order_item_id',
        'in_stock_adjustment_item_id',
        'transaction_symbol',
        'transaction_name',
        'transaction_code',
        'transaction_date',
        'transaction_category',
        'out_ic_goods_request_item_id',
        'out_ic_goods_request_item_out_id',
        'out_stock_adjustment_item_id',
        'out_ic_goods_borrow_item_id',
        'out_goods_return_code',
        'out_goods_repairment_code',
        'out_quantity',
        'out_item_unit_conversion_id',
        'cancel_quantity',
        'cancel_item_unit_conversion_id',
        'cancel_ic_goods_request_item_return_id',
        'status',
        'borrow_status',
        'repairment_status',
        'data_status',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'code');
    }

    public function outUnitConversion()
    {
        return $this->belongsTo(ItemUnitConversion::class, 'out_item_unit_conversion_id', 'id');
    }

    public function outGoodsRequestItem()
    {
        return $this->belongsTo(GoodsRequestItems::class, 'out_ic_goods_request_item_id', 'id');
    }

    public function outGoodsRequestItemOut()
    {
        return $this->belongsTo(GoodsRequestItemOut::class, 'out_ic_goods_request_item_out_id', 'id');
    }

    public function outStockAdjustmentItem()
    {
        return $this->belongsTo(StockAdjustmentItem::class, 'out_stock_adjustment_item_id', 'id');
    }

    public function outGoodsBorrowItem()
    {
        return $this->belongsTo(GoodsBorrowItem::class, 'out_ic_goods_borrow_item_id', 'id');
    }

    public function outGoodsReturn()
    {
        return $this->belongsTo(GoodsReturn::class, 'out_goods_return_code', 'code');
    }

    public function outGoodsRepairment()
    {
        return $this->belongsTo(GoodsRepairment::class, 'out_goods_repairment_code', 'code');
    }

    public function inDeliveryOrderItem()
    {
        return $this->belongsTo(DeliveryOrderItem::class, 'in_delivery_order_item_id', 'id');
    }

    public function inStockAdjustmentItem()
    {
        return $this->belongsTo(StockAdjustmentItem::class, 'in_stock_adjustment_item_id', 'id');
    }

    public function inPurchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItems::class, 'in_purchasing_purchase_order_item_id', 'id');
    }
}
