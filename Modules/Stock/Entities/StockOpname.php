<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class StockOpname extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table    = "stock_opnames";

    // Approvals Status
    const APPROVALS_WAITING   = 0;
    const APPROVALS_APPROVED  = 1;
    const APPROVALS_REJCTED   = 2;

    // Status
    const ENTRY         = 0;
    const APPROVALS     = 1;
    const DONE          = 2;

    const STOCK_WAITING = 0;
    const STOCK_BALANCE = 1;
    const STOCK_MIN     = 2;
    const STOCK_PLUS    = 3;

    const DATA_OPEN     = 0;
    const DATA_LOCK     = 1;


    protected $fillable = [
        'stock_opname_group_id',
        'stockopname_type',
        'item_code',
        'item_unit_conversion_id',
        'quantity_prev',
        'quantity_new',
        'quantity_issue',
        'quantity_adjustment',
        'quantity_issue_approved',
        'note',
        'issue_date',
        'issued_by',
        'approvals_status',
        'approvals_by',
        'status',
        'stock_status',
        'data_status',


    ];

    protected static $logName = 'StokOpname';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'code');
    }

    public function stockOpnameGroup()
    {
        return $this->belongsTo(StockOpnameGroup::class, 'stock_opname_group_id', 'id');
    }

    public function unitConversion()
    {
        return $this->belongsTo(ItemUnitConversion::class, 'item_unit_conversion_id', 'id');
    }

    public function employeeIsseudBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }

    public function employeeApprovalsBy()
    {
        return $this->belongsTo(HREmployee::class, 'approvals_by', 'nik');
    }
}
