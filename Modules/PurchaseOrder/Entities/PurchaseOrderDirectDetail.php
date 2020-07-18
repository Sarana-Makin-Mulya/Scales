<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Stock\Entities\Item;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseOrderDirectDetail extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "purchasing_purchase_order_direct_details";

    const REQUEST         = 1;
    const PROCESS         = 2;
    const PARTLY          = 3;
    const COMPLETED       = 4;
    const EXCESS          = 5;
    const CANCEL          = 6;

    protected $fillable = [
        'purchasing_purchase_order_direct_id',
        'purchasing_purchase_order_item_id',
        'item_code',
        'price',
        'quantity',
        'item_unit_conversion_id',
        'description',
        'is_active',
        'status',
    ];

    protected static $logName = 'Purchase Order (PO)';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function purchaseOrderDirect()
    {
        return $this->belongsTo(PurchaseOrderDirect::class, 'purchasing_purchase_order_direct_id', 'id');
    }

    public function purchaseOrderItem()
    {
     return $this->belongsTo(PurchaseOrderItems::class, 'purchasing_purchase_order_item_id', 'id');
    }

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_code', 'code');
    }
}
