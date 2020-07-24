<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class MonthlyClosing extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table    = "monthly_closings";

    protected $fillable = [
        'issue_date',
        'issued_by',
        'note',
    ];

    protected static $logName = 'Monthly Closings';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function monthlyClosing()
    {
        return $this->hasMany(StockTransaction::class, 'monthly_closing_id', 'id');
    }
}
