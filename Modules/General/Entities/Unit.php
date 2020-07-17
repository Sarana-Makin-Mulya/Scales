<?php

namespace Modules\General\Entities;

use Modules\Stock\Entities\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Unit extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'general_units';

    protected $fillable = [
        'measure_code',
        'name',
        'symbol',
        'description',
        'is_active',
    ];

    protected static $logName = 'Master Satuan';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function unit()
    {
        return $this->belongsTo(Item::class, 'unit_id', 'id');
    }
}
