<?php

namespace Modules\Supplier\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Stock\Entities\ItemCategory;

class SupplierHasItemCategory extends Model
{
    use SoftDeletes;
    protected $table   = "supplier_has_item_categories";
    protected $fillable = [
        'supplier_code',
        'item_categori_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_code', 'code');
    }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class, 'item_categori_id', 'id');
    }
}
