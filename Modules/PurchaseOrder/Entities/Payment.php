<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\Currency;
use Modules\HumanResource\Entities\HREmployee;
use Modules\Supplier\Entities\Supplier;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use SoftDeletes;
    use LogsActivity;
    public $incrementing   = false;
    protected $primaryKey  = "code";
    protected $table       = "purchasing_payments";

    const ACTIVE           = 1;
    const CANCEL           = 2;

    const PAYMENT_CASH     = 1;
    const PAYMENT_TRANSFER = 0;

    protected $fillable = [
        'code',
        'supplier_code',
        'currency',
        'exchange_rate',
        'nominal',
        'supplier_saldo',
        'payment_date',
        'payment_method',
        'company_bank_account',
        'supplier_bank_account',
        'payment_img',
        'payment_note',
        'issue_date',
        'issued_by',
        'is_active',
        'status',
    ];

    protected static $logName = 'Payment';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function paymentDetail()
    {
        return $this->hasMany(PaymentDetail::class, 'payment_code', 'code');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_code', 'code');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }

    public function generalCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency', 'id');
    }
}
