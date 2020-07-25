<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\Currency;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class JunkItemSpk extends Model
{
    use SoftDeletes;
    use LogsActivity;
    public $incrementing  = false;
    protected $primaryKey = "code";
    protected $table      = "junk_item_spks";

    const BUYER_INTERNAL  = 1;
    const BUYER_EXTERNAL  = 2;

    const PAY_PENDING     = 1;
    const PAY_PARTLY      = 2;
    const PAY_PAID        = 3;

    const REQUEST         = 1;
    const PROCESS         = 2;
    const DONE            = 3;
    const CANCEL          = 4;

    const DATA_OPEN       = 0;
    const DATA_LOCK       = 1;

    const WH_WAITING      = 1;
    const WH_PARTLY       = 2;
    const WH_COMPLETED    = 3;

    protected $fillable = [
        'code',
        'buyer_type',
        'buyer_nik',
        'buyer_code',
        'buyer_name',
        'buyer_pic',
        'buyer_address',
        'buyer_phone',
        'buyer_arrivals_date',
        'smm_pic_nik',
        'smm_pic_name',
        'currency_id',
        'exchange_rate',
        'payment_due',
        'payment_status',
        'note',
        'issue_date',
        'issued_by',
        'status',
        'status_data',
    ];

    protected static $logName = 'SPK BS';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function junkItemSpkDetail()
    {
        return $this->hasMany(JunkItemSpkDetail::class, 'junk_item_spk_code', 'code');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }

    public function buyerInternal()
    {
        return $this->belongsTo(HREmployee::class, 'buyer_nik', 'nik');
    }

    public function buyerExternal()
    {
        return $this->belongsTo(JunkItemBuyer::class, 'buyer_code', 'code');
    }

    public function employeePIC()
    {
        return $this->belongsTo(HREmployee::class, 'smm_pic', 'nik');
    }

    public function generalCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
}
