<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HREmployeeAuthorization extends Model
{
    use SoftDeletes;
    protected $table    = "hrd_employee_authorizations";

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function detail()
    {
        return $this->hasMany(HREmployeeAuthorizationDetail::class, 'employee_authorization_id', 'id');
    }
}
