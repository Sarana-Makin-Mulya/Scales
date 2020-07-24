<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class StockAdjustmentItem extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "stock_adjustment_items";

    // Approvals Status
    const APPROVALS_PENDING  = 1;
    const APPROVALS_APPROVED = 2;
    const APPROVALS_REJECTED = 3;

    // Status Process
    const REQUEST            = 1;
    const PROCESS            = 2;
    const DONE               = 3;

    // Status Data
    const DATA_OPEN          = 0;
    const DATA_LOCK          = 1;


    protected $fillable = [
        'stock_adjustment_code',
        'item_code',
        'item_unit_conversion_id',
        'quantity',
        'stock_adjustment_category_id',
        'description',
        'stock_quarantine_id',
        'stock_opname_group_id',
        'stock_opname_id',
        'issue_date',
        'issued_by',
        'approvals_status',
        'approvals_date',
        'approvals_by',
        'approvals_note',
        'release_qty',
        'release_date',
        'release_by',
        'release_note',
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

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class, 'stock_opname_id', 'id');
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class, 'stock_adjustment_code', 'code');
    }

    public function items()
    {
        return $this->belongsTo(Item::class, 'item_code', 'code');
    }

    public function conversionUnits()
    {
        return $this->belongsTo(ItemUnitConversion::class, 'item_unit_conversion_id', 'id');
    }

    public function employeeIssuedBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }

    public function stockAdjustmentCategory()
    {
        return $this->belongsTo(StockAdjustmentCategory::class, 'stock_adjustment_category_id', 'id');
    }

    public function employeeApprovalsBy()
    {
        return $this->belongsTo(HREmployee::class, 'approvals_by', 'nik');
    }

    public function employeeReleaseBy()
    {
        return $this->belongsTo(HREmployee::class, 'release_by', 'nik');
    }
}
