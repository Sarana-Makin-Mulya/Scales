<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\Unit;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemUnitConversion extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $fillable = [
        'item_code',
        'unit_id',
        'conversion_value',
        'is_primary',
    ];

    protected static $logName = 'Master Konversi Satuan';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'code');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
