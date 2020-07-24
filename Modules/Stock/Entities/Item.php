<?php

namespace Modules\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\Unit;
use Modules\PurchaseOrder\Entities\PurchaseRequestItemDetail;
use Modules\PurchaseOrder\Entities\PurchaseRequestItems;
use Spatie\Activitylog\Traits\LogsActivity;

class Item extends Model
{
    use SoftDeletes;
    use LogsActivity;
    public $incrementing  = false;
    protected $primaryKey = "code";
    protected $table      = "items";

    protected $fillable = [
        'code',
        'old_code',
        'item_category_id',
        'item_brand_id',
        'item_measure_id',
        'name',
        'slug',
        'nickname',
        'type',
        'size',
        'color',
        'detail',
        'description',
        'info',
        'is_priority',
        'borrowable',
        'max_stock',
        'min_stock',
        'current_stock',
        'is_active',
        'status_stock',
        'stock_app_old_id',
        'create_by',
    ];

    protected static $logName = 'Master Barang';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function images()
    {
        return $this->hasMany(ItemImage::class, 'item_code', 'code');
    }

    public function unitConversion()
    {
        return $this->hasMany(ItemUnitConversion::class, 'item_code', 'code');
    }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'item_brand_id', 'id');
    }

    public function unitMeasure()
    {
        return $this->belongsTo(Unit::class, 'item_measure_id', 'measure_code');
    }

    public function PurchaseRequestItemDetail()
    {
       return $this->hasMany(PurchaseRequestItemDetail::class, 'item_code', 'code');
    }

    public function StockTransaction()
    {
       return $this->hasMany(StockTransaction::class, 'item_code', 'code');
    }

    // public function unit()
    // {
    //     return $this->belongsTo(Unit::class, 'unit_id', 'id');
    // }
}
