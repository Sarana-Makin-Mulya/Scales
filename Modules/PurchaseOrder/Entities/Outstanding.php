<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class Outstanding extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "purchasing_outstandings";

    const DATA_OPEN       = 0;
    const DATA_LOCK       = 1;

    const PAY_OUTSTANDING = 1;
    const PAY_PARTLY      = 2;
    const PAY_PAID        = 3;
    const PAY_OVERPAID    = 4;


    protected $fillable = [
        'purchasing_purchase_order_code',
        'purchasing_service_order_code',
        'supplier_code',
        'payment_type',
        'due_date',
        'currency',
        'exchange_rate',
        'terms_number',
        'terms_flat',
        'terms_range',
        'terms_unit',
        'ppn',
        'ppn_percen',
        'ppn_nominal',
        'ppn_status',
        'discon',
        'discon_percen',
        'discon_nominal',
        'discon_reason',
        'issue_date',
        'issued_by',
        'is_active',
        'status',
        'status_data',
    ];

    protected static $logName = 'Outstaning (PO)';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function purchaseOrder()
    {
        return $this->hasMany(PurchaseOrder::class, 'purchasing_purchase_order_code', 'code');
    }

    public function serviceOrder()
    {
        return $this->hasMany(ServiceOrder::class, 'purchasing_service_order_code', 'code');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_code', 'code');
    }

    public function outstandingDetails()
    {
        return $this->hasMany(OutstandingDetail::class, 'outstanding_id', 'id');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }

    public function generalCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency', 'id');
    }

    public function payment()
    {
        return $this->hasMany(outstanding::class, 'outstanding_id', 'id');
    }
}
