<?php

namespace Modules\PurchaseOrder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class JunkItemBuyer extends Model
{
    use SoftDeletes;
    use LogsActivity;
    public $incrementing  = false;
    protected $primaryKey = "code";
    protected $table      = "junk_item_buyers";

    protected $fillable = [
        'code',
        'name',
        'pic',
        'phone',
        'address',
        'is_active',
    ];

    protected static $logName = 'Daftar Penadah Barang Sampah';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function junkItemPrice()
    {
        return $this->hasMany(JunkItemPrice::class, 'junk_item_buyer_code', 'code');
    }
}
