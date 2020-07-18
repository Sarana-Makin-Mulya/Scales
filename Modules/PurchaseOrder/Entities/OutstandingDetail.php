<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class OutstandingDetail extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "purchasing_outstanding_details";

    const DATA_OPEN       = 0;
    const DATA_LOCK       = 1;

    const PAY_OUTSTANDING = 1;
    const PAY_PARTLY      = 2;
    const PAY_PAID        = 3;
    const PAY_OVERPAID    = 4;

    protected $fillable = [
        'outstanding_id',
        'purchasing_purchase_order_code',
        'purchasing_service_order_code',
        'terms_number',
        'nominal',
        'percen',
        'due_date',
        'status',
        'issue_date',
        'issued_by',
        'is_active',
        'status',
        'status_data',
    ];

    protected static $logName = 'Outstanding Detail';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

   // Relations Table
    public function outstanding()
    {
        return $this->belongsTo(OutstandingDetail::class, 'outstanding_id', 'id');
    }

    public function paymentDetail()
    {
        return $this->belongsTo(outstandingDetail::class, 'outstanding_detail_id', 'id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchasing_purchase_order_code', 'code');
    }

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class, 'purchasing_service_order_code', 'code');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }
}
