<?php

namespace Modules\Regional\Entities;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'regional_districts';

    protected $fillable = [
        'id',
        'regency_id',
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
     * @return Modules\Regional\Entities\RegionalRegency
     */
    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    /**
     * Relation to table regency
     *
     * @return Modules\Regional\Entities\RegionalRegency
     */
    public function villages()
    {
        return $this->hasMany(Village::class, 'district_id', 'id');
    }
}
