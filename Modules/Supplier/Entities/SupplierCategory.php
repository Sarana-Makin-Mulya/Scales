<?php

namespace Modules\Supplier\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierCategory extends Model
{
    use SoftDeletes;
    protected $table   = "supplier_categories";

    protected $guarded = [];

    public function supplier()
    {
        return $this->hasMany(Supplier::class, 'supplier_category_id', 'id');
    }
}
