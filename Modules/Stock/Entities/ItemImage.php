<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ItemImage extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $fillable = [
        'item_code',
        'path',
        'disk',
    ];

    protected static $logName = 'Master Gambar Barang';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'code');
    }
}
