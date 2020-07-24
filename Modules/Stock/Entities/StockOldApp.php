<?php
namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StockOldApp extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $table    = "stock_old_apps";

    protected $fillable = [
        'item_old_code',
        'item_code',
        'name',
        'size',
        'tipe',
        'brand',
        'color',
        'moq',
        'qty_borrow',
        'qty_stock',
        'unit_name',
        'unit_id',
    ];

    protected static $logName = 'Stok aplikasi Lama';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_old_code', 'old_code');
    }
}
