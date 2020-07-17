<?php

namespace Modules\Supplier\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\Currency;
use Modules\HumanResource\Entities\HREmployee;
use Modules\PurchaseOrder\Entities\Payment;
use Spatie\Activitylog\Traits\LogsActivity;

class SupplierSaldo extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table    = "supplier_saldos";

    const DEBIT         = 0;
    const CREDIT        = 1;

    const OVERPAYMENT   = 1;
    const USEDPAYMENT   = 2;
    const TAKEDEBIT     = 3;


    protected $fillable = [
        'supplier_code',
        'payment_code',
        'transaction',
        'status',
        'currency',
        'exchange_rate',
        'nominal',
        'used',
        'nominal_active',
        'description',
        'issue_date',
        'issued_by',
        'is_active',
    ];

    protected static $logName       = 'Supplier Saldo';
    protected static $logFillable   = true;
    protected static $logOnlyDirty  = true;

    // Relations Table
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_code', 'code');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_code', 'code');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency', 'id');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }
}
