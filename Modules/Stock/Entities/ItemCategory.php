<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemCategory extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $fillable = [
        'code',
        'old_code',
        'name',
        'slug',
        'is_active',
    ];

    protected static $logName = 'Master Kategori Barang';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function item()
    {
        return $this->hasMany(Item::class, 'item_category_id', 'id');
    }
}
