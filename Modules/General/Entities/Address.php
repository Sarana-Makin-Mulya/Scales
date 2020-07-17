<?php

namespace Modules\General\Entities;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = "general_addresses";

    protected $fillable = [
        'full_address',
        'province',
        'regency',
        'district',
        'villages',
        'full_location_ids',
    ];
}
