<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    protected $table = 'general_contact_types';

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Relation to contact table
     * @return Modules\General\Entities\Contact
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'contact_type_id', 'id');
    }
}
