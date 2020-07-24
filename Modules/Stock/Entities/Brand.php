<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Brand extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'item_brands';

    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    protected static $logName = 'Master Merek';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function items()
    {
        return $this->hasMany(Item::class, 'item_brand_id', 'id');
    }
}
