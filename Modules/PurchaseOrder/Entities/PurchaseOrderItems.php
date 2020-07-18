<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\ItemUnitConversion;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseOrderItems extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "purchasing_purchase_order_items";

    const PROCESS         = 1;
    const PARTLY          = 2;
    const COMPLETED       = 3;
    const EXCESS          = 4;
    const CANCEL          = 5;

    const SQO_WAITING     = 1;
    const SQO_PARTLY      = 2;
    const SQO_COMPLETED   = 3;
    const SQO_EXCESS      = 4;

    protected $fillable = [
        'purchasing_purchase_order_code',
        'ic_purchase_request_item_id',
        'item_code',
        'item_detail',
        'item_unit_conversion_id',
        'item_unit',
        'quantity',
        'price',
        'description',
        'target_arrival_date',
        'is_active',
        'status',
        'status_quantity_order',
        'total_quantity_order',
        'total_quantity_return',
    ];

    protected static $logName = 'Purchase Order (PO)';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

   // Relations Table
   public function purchaseOrderItemdetail()
   {
       return $this->hasMany(PurchaseOrderItemDetail::class, 'purchasing_purchase_order_item_id', 'id');
   }

   public function items()
   {
       return $this->belongsTo(Item::class, 'item_code', 'code');
   }

   public function conversionUnits()
   {
       return $this->belongsTo(ItemUnitConversion::class, 'item_unit_conversion_id', 'id');
   }

   public function purchaseOrder()
   {
    return $this->belongsTo(PurchaseOrder::class, 'purchasing_purchase_order_code', 'code');
   }

   public function purchaseRequestItem()
   {
       return $this->belongsTo(PurchaseRequestItems::class, 'ic_purchase_request_item_id', 'id');
   }

   public function discount()
    {
        return $this->hasMany(OutstandingDiscon::class, 'purchasing_purchase_order_item_id', 'id');
    }
}
