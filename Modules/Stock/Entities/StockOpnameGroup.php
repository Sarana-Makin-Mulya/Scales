<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HumanResource\Entities\HREmployee;
use Spatie\Activitylog\Traits\LogsActivity;

class StockOpnameGroup extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table    = "stock_opname_groups";

    const PROCESS       = 1;
    const COMPLETED     = 2;
    const CANCELED      = 3;

    protected $fillable = [
        'file_name',
        'total_item',
        'issue_date',
        'issued_by',
        'type',
        'status',
    ];

    protected static $logName = 'StokOpname Group';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;


    public function stockOpname()
    {
        return $this->hasMany(StockOpname::class, 'stock_opname_group_id', 'id');
    }

    public function employeeIsseudBy()
    {
        return $this->belongsTo(HREmployee::class, 'issued_by', 'nik');
    }

}
