<?php

namespace Modules\Supplier\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierAddress extends Model
{
    use SoftDeletes;
    protected $table   = "supplier_addresses";
    protected $fillable = [
        'supplier_code',
        'address_type',
        'full_address',
        'province',
        'regency',
        'district',
        'village',
        'zipcode',
        'full_location_ids',
        'is_primary',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_code', 'code');
    }
}
