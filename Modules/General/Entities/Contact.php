<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Supplier\Entities\SupplierContact;

class Contact extends Model
{
    protected $table = 'general_contacts';

    protected $fillable = [
        'contact_type_id',
        'contact',
        'note',
    ];

    /**
     * Relation to contact types table
     * @return Modules\General\Entities\ContactType
     */
    public function contactType()
    {
        return $this->belongsTo(ContactType::class, 'contact_type_id', 'id');
    }

    public function supplierContact()
    {
        return $this->hasMany(SupplierContact::class, 'general_contact_id', 'id');
    }
}
