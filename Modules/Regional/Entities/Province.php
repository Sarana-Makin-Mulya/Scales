<?php

namespace Modules\Regional\Entities;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'regional_provinces';

    protected $fillable = [
        'id',
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
     * Relation to regencies table
     *
     * @returt Modules\Regional\Entities\RegionalRegency
     */
    public function regencies()
    {
        return $this->hasMany(Regency::class, 'province_id', 'id');
    }
}
