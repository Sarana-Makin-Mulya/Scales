<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StockAdjustmentCategory extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table      = "stock_adjustment_categories";

    const STOCK_IN  = 1;
    const STOCK_OUT = 0;

    protected $fillable = [
        'name',
        'stock',
        'type_value',  // 1 : Goods Value , 2 : Penalty
    ];

    protected static $logName = 'Master Kategori Penyesuaian Stok';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function stockAdjustmentItem()
    {
        return $this->belongsTo(StockAdjustmentCategory::class, 'stock_adjustment_category_id', 'id');
    }
}
