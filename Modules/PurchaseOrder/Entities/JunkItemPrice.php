<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\Currency;
use Modules\General\Entities\Unit;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class JunkItemPrice extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "junk_item_prices";

    const PRICE_NORMAL = 1;
    const PRICE_DISCON = 2;

    const APPRPVALS_WAITING  = 1;
    const APPROVALS_ACCEPTED = 2;
    const APPROVALS_REJECTED = 3;

    protected $fillable = [
        'junk_item_buyer_code',
        'junk_item_code',
        'type',
        'currency_id',
        'price',
        'unit_id',
        'desc',
        'approvals_type',
        'approvals_file',
        'approvals_by',
        'approvals_date',
        'approvals_status',
        'approvals_note',
        'is_active',
    ];

    protected static $logName = 'Daftar Harga Barang Sampah';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    public function buyer()
    {
        return $this->belongsTo(JunkItemBuyer::class, 'junk_item_buyer_code', 'code');
    }

    public function item()
    {
        return $this->belongsTo(JunkItem::class, 'junk_item_code', 'code');
    }

    public function employeeAapprovalsBy()
    {
        return $this->belongsTo(HREmployee::class, 'approvals_by', 'nik');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
