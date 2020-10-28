<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\Currency;
use Modules\HumanResource\Entities\HREmployee;
use Modules\Supplier\Entities\Supplier;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseOrder extends Model
{
    use SoftDeletes;
    use LogsActivity;
    public $incrementing  = false;
    protected $primaryKey = "code";
    protected $table      = "purchasing_purchase_orders";

    const PROCESS         = 1;
    const DONE            = 2;
    const CANCEL          = 3;

    const PO_SUPPLIER     = 1;
    const PO_DIRECTLY     = 2;
    const PO_COAL         = 3;

    const DATA_OPEN       = 0;
    const DATA_LOCK       = 1;

    const DO_WAITING      = 1;
    const DO_PARTLY       = 2;
    const DO_COMPLETED    = 3;
    const DO_EXCESS       = 4;

    const PAY_PENDING     = 1;
    const PAY_PARTLY      = 2;
    const PAY_PAID        = 3;

    const PPN_INCLUDE     = 1;
    const PPN_EXCLUDE     = 0;

    protected $fillable = [
        'code',
        'po_type',
        'supplier_code',
        'supplier_name',
        'supplier_pic',
        'supplier_address',
        'supplier_phone',
        'due_date',
        'pic',
        'pic_name',
        // 'ppn',
        // 'ppn_status',
        // 'ppn_percen',
        // 'ppn_nominal',
        // 'currency',
        // 'exchange_rate',
        // 'payment_type',
        // 'payment_due',
        // 'payment_terms',
        // 'payment_terms_flat',
        // 'payment_terms_range',
        // 'payment_terms_unit',
        // 'payment_terms_due',
        // 'payment_status',
        // 'payment_note',
        'note',
        'issue_date',
        'issued_by',
        'status',
        'status_data',
        'status_do',
    ];

    protected static $logName = 'Purchase Order (PO)';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function outstanding()
    {
        return $this->hasMany(Outstanding::class, 'purchasing_purchase_order_code', 'code');
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItems::class, 'purchasing_purchase_order_code', 'code');
    }

    public function purchaseOrderTermins()
    {
        return $this->hasMany(PurchaseOrderTermin::class, 'purchasing_purchase_order_code', 'code');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_code', 'code');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }

    public function employeePIC()
    {
        return $this->belongsTo(HREmployee::class, 'pic', 'nik');
    }

    public function generalCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency', 'id');
    }
}
