<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StockQuarantine extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table   = "stock_quarantines";


    // action
    const QUARANTINE        = 1;
    const DEAD_STOCK        = 2;

    // approvals
    const NOT_APPROVALS     = 0;
    const WAITING_APPROVALS = 1;
    const APPROVED          = 2;
    const REJECTED          = 3;

    // status
    const PENDING           = 0;
    const APPROVALS         = 1;
    const RETURN_TO_STOCK   = 2;
    const DONE              = 3;

    protected $fillable = [
        'item_code',
        'stock_transaction_id',
        'quantity',
        'release_qty',
        'item_unit_conversion_id',
        'action',
        'action_date',
        'issue_date',
        'issued_by',
        'reason',
        'approvals',
        'status',
        'data_status',
        'is_active',
    ];

    protected static $logName = 'Karantina/Dead stock';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'code');
    }

    public function StockTransaction()
    {
        return $this->belongsTo(StockTransaction::class, 'stock_transaction_id', 'id');
    }
}
