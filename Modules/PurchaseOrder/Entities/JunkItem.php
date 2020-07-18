<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\Unit;
use Spatie\Activitylog\Traits\LogsActivity;

class JunkItem extends Model
{
    use SoftDeletes;
    use LogsActivity;
    public $incrementing  = false;
    protected $primaryKey = "code";
    protected $table      = "junk_items";

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'unit_id',
        'is_active',
    ];

    protected static $logName = 'Daftar Barang Sampah';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function junkItemPrice()
    {
        return $this->hasMany(JunkItemPrice::class, 'junk_item_code', 'code');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
