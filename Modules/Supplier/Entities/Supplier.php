<?php

namespace Modules\Supplier\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    public $incrementing = false;
    protected $primaryKey = "code";
    protected $table   = "suppliers";

    protected $guarded = [];

    public function contacts()
    {
        return $this->hasMany(SupplierContact::class, 'supplier_code', 'code');
    }

    public function addresses()
    {
        return $this->hasMany(SupplierAddress::class, 'supplier_code', 'code');
    }

    public function supplierCategory()
    {
        return $this->belongsTo(SupplierCategory::class, 'supplier_category_id', 'id');
    }
}
