<?php

namespace Modules\PublicWarehouse\Entities;

use Illuminate\Database\Eloquent\Model;

class WeighingItems extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public $incrementing  = false;
    protected $primaryKey = "code";
    protected $table      = "weighing_items";

    protected $fillable = [
        'code',
        'name',
        'slug',
        'description',
    ];


    protected static $logName = 'Master Barang Penimbangan';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function weighing()
    {
        return $this->hasMany(Weighing::class, 'weighing_category_id', 'id');
    }
}
