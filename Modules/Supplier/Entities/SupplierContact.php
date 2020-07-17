<?php

namespace Modules\Supplier\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\General\Entities\ContactType;

class SupplierContact extends Model
{
    use SoftDeletes;
    protected $table   = "supplier_contacts";
    protected $fillable = [
        'supplier_code',
        'general_contact_type_id',
        'contact',
        'note',
        'is_primary',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_code', 'code');
    }

    public function contactType()
    {
        return $this->belongsTo(ContactType::class, 'general_contact_type_id', 'id');
    }
}
