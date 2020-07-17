<?php

namespace Modules\Regional\Entities;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $table = 'regional_villages';

    protected $fillable = [
        'district_id',
        'name',
    ];

    /**
     * name field mutator
     *
     * @param string $name
     * @return string $name
     */
    public function getNameAttribute($name)
    {
        return  ucfirst(strtolower($name));
    }

    /**
     * Relation to table regency
     *
     * @return Modules\Regional\Entities\RegionalDistrict
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }
}
