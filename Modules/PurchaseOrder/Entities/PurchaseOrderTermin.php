<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseOrderTermin extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "purchasing_purchase_order_termins";

    const PAY_PENDING     = 1;
    const PAY_PARTLY      = 2;
    const PAY_PAID        = 3;

    protected $fillable = [
        'purchasing_purchase_order_code',
        'nominal',
        'percen',
        'due_date',
        'pay_status',
        'is_active',
    ];

    protected static $logName = 'Purchase Order (PO)';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

   // Relations Table
   public function purchaseOrder()
   {
    return $this->belongsTo(PurchaseOrder::class, 'purchasing_purchase_order_code', 'code');
   }
}
