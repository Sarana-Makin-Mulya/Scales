<?php

namespace Modules\PublicWarehouse\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class WeighingCategory extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table      = "weighing_categories";

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];


    protected static $logName = 'Master Kategori Penimbangan Barang';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function weighing()
    {
        return $this->hasMany(Weighing::class, 'weighing_category_id', 'id');
    }
}
