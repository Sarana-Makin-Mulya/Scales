<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HumanResource\Entities\HREmployee;
use Modules\Supplier\Entities\Supplier;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseOrderDirect extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "purchasing_purchase_order_directs";

    const DATA_OPEN       = 0;
    const DATA_LOCK       = 1;

    const DO_WAITING      = 1;
    const DO_PARTLY       = 2;
    const DO_COMPLETED    = 3;
    const DO_EXCESS       = 4;

    protected $fillable = [
        'purchasing_purchase_order_code',
        'purchasing_purchase_order_code_suffix',
        'supplier_code',
        'purchase_date',
        'issue_date',
        'note',
        'is_active',
        'issued_by',
        'status_data',
        'status_do',
    ];

    protected static $logName = 'Purchase Order (PO)';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function purchaseOrderDirectDetail()
    {
        return $this->hasMany(PurchaseOrderDirectDetail::class, 'purchasing_purchase_order_direct_id', 'id');
    }

    public function purchaseOrder()
    {
     return $this->belongsTo(PurchaseOrder::class, 'purchasing_purchase_order_code', 'code');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_code', 'code');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }
}
