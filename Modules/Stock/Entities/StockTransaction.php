<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PublicWarehouse\Entities\DeliveryOrderItem;
use Modules\PublicWarehouse\Entities\GoodsReleaseItem;
use Modules\PublicWarehouse\Entities\GoodsRepairment;
use Modules\PublicWarehouse\Entities\GoodsRequestItemOut;
use Modules\PublicWarehouse\Entities\goodsRequestItemReturn;
use Modules\PublicWarehouse\Entities\GoodsRequestItems;
use Modules\PublicWarehouse\Entities\GoodsReturn;

class StockTransaction extends Model
{
    use SoftDeletes;
    protected $table    = "stock_transactions";

    // Entry Status
    const STOCK_SUMMARY = 0;
    const STOCK_IN      = 1;
    const STOCK_OUT     = 2;

    // Data Status
    const DATA_OPEN     = 0;
    const DATA_LOCK     = 1;

    // Status
    const STATUS_PROCESS= 0;
    const STATUS_DONE   = 1;
    const STATUS_CANCEL = 2;

    // Stock Status
    const STOCK_EMPTY     = 0;
    const STOCK_AVAILABLE = 1;

    // Stock Category (SC)
    const SC_ACTIVE = 1;
    const SC_QUARANTINE = 2;
    const SC_DEAD_STOCK = 3;


    protected $fillable = [
        'item_code',
        'quantity',
        'stock_out',
        'stock_current',
        'item_unit_conversion_id',
        'po_code',
        'transaction_symbol',
        'transaction_name',
        'transaction_code',
        'transaction_date',
        'ic_goods_request_item_id',
        'ic_goods_request_item_out_id',
        'ic_goods_request_item_return_id',
        'ic_goods_borrow_item_id',
        'stock_adjustment_item_id',
        'delivery_order_item_id',
        'ic_goods_return_id',
        'goods_repairment_code',
        'stock_quarantine_id',
        'stock_quarantine_date',
        'stock_category',
        'storaged',
        'entry_status',
        'status',
        'data_status',
        'stock_status',
        'stock_closing_id',
        'note',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'code');
    }

    public function unitConversion()
    {
        return $this->belongsTo(ItemUnitConversion::class, 'item_unit_conversion_id', 'id');
    }

    public function goodsRepairment()
    {
        return $this->belongsTo(GoodsRepairment::class, 'goods_repairment_code', 'code');
    }

    public function goodsRequestItem()
    {
        return $this->belongsTo(GoodsRequestItems::class, 'ic_goods_request_item_id', 'id');
    }

    public function goodsRequestItemOut()
    {
        return $this->belongsTo(GoodsRequestItemOut::class, 'ic_goods_request_item_out_id', 'id');
    }

    public function goodsRequestItemReturn()
    {
        return $this->belongsTo(goodsRequestItemReturn::class, 'ic_goods_request_item_return_id', 'id');
    }

    public function stockAdjustmentItem()
    {
        return $this->belongsTo(StockAdjustmentItem::class, 'stock_adjustment_item_id', 'id');
    }

    public function deliveryOrderItem()
    {
        return $this->belongsTo(DeliveryOrderItem::class, 'delivery_order_item_id', 'id');
    }

    public function goodsReturn()
    {
        return $this->belongsTo(GoodsReturn::class, 'ic_goods_return_id', 'code');
    }

    public function stockClosing()
    {
        return $this->belongsTo(StockClosing::class, 'stock_closing_id', 'id');
    }
}
