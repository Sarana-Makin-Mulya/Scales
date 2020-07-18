<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentDetail extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "purchasing_payment_details";

    const ACTIVE          = 1;
    const CANCEL          = 2;

    protected $fillable = [
        'payment_code',
        'termin',
        'po_type',
        'purchasing_purchase_order_code',
        'purchasing_service_order_code',
        'outstanding_id',
        'outstanding_detail_id',
        'currency',
        'exchange_rate',
        'payment_nominal',
        'payment_date',
        'payment_img',
        'issue_date',
        'issued_by',
        'status',
        'is_active',
    ];

    protected static $logName = 'Payment Detail';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_code', 'code');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }

    public function purchaseOrder()
    {
        return $this->hasMany(PurchaseOrder::class, 'purchasing_purchase_order_code', 'code');
    }

    public function serviceOrder()
    {
        return $this->hasMany(ServiceOrder::class, 'purchasing_service_order_code', 'code');
    }

    public function outstanding()
    {
        return $this->belongsTo(outstanding::class, 'outstanding_id', 'id');
    }

    public function outstandingDetail()
    {
        return $this->belongsTo(outstandingDetail::class, 'outstanding_detail_id', 'id');
    }
}
