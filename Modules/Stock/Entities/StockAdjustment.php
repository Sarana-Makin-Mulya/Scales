<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class StockAdjustment extends Model
{
    use SoftDeletes;
    use LogsActivity;
    public $incrementing  = false;
    protected $primaryKey = "code";
    protected $table      = "stock_adjustments";

    // Status Process
    const REQUEST            = 1;
    const PROCESS_APPROVALS  = 2;
    const WAITING_REPORT     = 3;
    const REJECTED           = 4;
    const DONE               = 5;

    // Status Data
    const DATA_OPEN          = 0;
    const DATA_LOCK          = 1;

    // Source
    const NORMAL             = 1;
    const STOCKOPNAME        = 2;
    const DEADSTOCK          = 3;
    const GOODSREPAIRMENT    = 4;

    protected $fillable = [
        'code',
        'type',
        'issue_date',
        'issued_by',
        'description',
        'stock_opname_group_id',
        'stock_opname_id',
        'status',
        'data_status',
        'is_active',
    ];

    protected static $logName = 'Penyesuaian Stok';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function stockOpnameGroup()
    {
        return $this->belongsTo(StockOpnameGroup::class, 'stock_opname_group_id', 'id');
    }

    public function stockAdjustmentItem()
    {
        return $this->hasMany(StockAdjustmentItem::class, 'stock_adjustment_code', 'code');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }
}
