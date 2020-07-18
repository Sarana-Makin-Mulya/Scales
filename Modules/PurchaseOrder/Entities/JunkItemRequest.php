<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\Currency;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class JunkItemRequest extends Model
{
    use SoftDeletes;
    use LogsActivity;
    public $incrementing  = false;
    protected $primaryKey = "code";
    protected $table      = "junk_item_requests";

    const PAY_PENDING     = 1;
    const PAY_PARTLY      = 2;
    const PAY_PAID        = 3;

    const PROCESS         = 1;
    const DONE            = 2;
    const CANCEL          = 3;

    const DATA_OPEN       = 0;
    const DATA_LOCK       = 1;

    const WH_WAITING      = 1;
    const WH_PARTLY       = 2;
    const WH_COMPLETED    = 3;

    protected $fillable = [
        'code',
        'buyer_code',
        'buyer_name',
        'buyer_pic',
        'buyer_address',
        'buyer_phone',
        'buyer_arrivals_date',
        'smm_pic',
        'smm_pic_name',
        'currency',
        'exchange_rate',
        'payment_due',
        'payment_status',
        'note',
        'issue_date',
        'issued_by',
        'status',
        'status_data',
        'status_weighing',
    ];

    protected static $logName = 'Request Penjualan Barang Sampah';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function junkItemRequestDetail()
    {
        return $this->hasMany(JunkItemRequestDetail::class, 'junk_item_request_code', 'code');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }

    public function buyer()
    {
        return $this->belongsTo(JunkItemBuyer::class, 'buyer_code', 'code');
    }

    public function employeePIC()
    {
        return $this->belongsTo(HREmployee::class, 'smm_pic', 'nik');
    }

    public function generalCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency', 'id');
    }
}
