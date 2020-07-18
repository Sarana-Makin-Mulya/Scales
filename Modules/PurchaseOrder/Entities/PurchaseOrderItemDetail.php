<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseOrderItemDetail extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table = "purchasing_purchase_order_item_details";

    protected $fillable = [
        'purchasing_purchase_order_item_id',
        'ic_purchase_request_code',
        'ic_purchase_request_item_id',
        'item_code',
        'quantity_req',
        'quantity_order',
        'quantity_po',
        'is_active',
    ];

    protected static $logName = 'Purchase Order (PO)';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

   // Relations Table
   public function purchaseOrderItem()
   {
       return $this->belongsTo(PurchaseOrderItems::class, 'purchasing_purchase_order_item_id', 'id');
   }

   public function purchaseRequestItem()
   {
       return $this->belongsTo(PurchaseRequestItems::class, 'ic_purchase_request_item_id', 'id');
   }
}
