<?php

namespace Modules\Regional\Entities;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $table = 'regional_regencies';

    protected $fillable = [
        'id',
        'name',
        'province_id',
        'sort_name',
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
     * Relation to table province
     *
     * @return Modules\Regional\Entities\RegionalProvince
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    /**
     * Relation to table district
     *
     * @return Modules\Regional\Entities\RegionalDistrict
     */
    public function districts()
    {
        return $this->hasMany(District::class, 'regency_id', 'id');
    }
}
