<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class OutstandingDiscon extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "purchasing_outstanding_dicsons";

    const DATA_OPEN       = 0;
    const DATA_LOCK       = 1;

    protected $fillable = [
        'outstanding_id',
        'purchase_order_code',
        'purchase_order_item_id',
        'service_order_code',
        'service_order_fee_id',
        'discon_percen',
        'discon_nominal',
        'note',
        'issue_date',
        'issued_by',
        'is_active',
        'status_data',
    ];

    protected static $logName = 'Outstanding Discon';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    // Relations Table
    public function outstanding()
    {
        return $this->belongsTo(OutstandingDetail::class, 'outstanding_id', 'id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchasing_purchase_order_code', 'code');
    }

    public function purchaseOrderItems()
    {
        return $this->belongsTo(PurchaseOrderItems::class, 'purchase_order_item_id', 'id');
    }

    public function serviceOrder()
    {
        return $this->belongsTo(serviceOrder::class, 'service_order_code', 'code');
    }

    public function serviceOrderFees()
    {
        return $this->belongsTo(ServiceOrderFee::class, 'service_order_fee_id', 'id');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }
}
