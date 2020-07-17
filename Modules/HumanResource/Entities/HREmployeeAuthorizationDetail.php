<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HREmployeeAuthorizationDetail extends Model
{
    use SoftDeletes;
    protected $table    = "hrd_employee_authorization_details";

    protected $fillable = [
        'employee_authorization_id',
        'department_id',
        'employee_nik',
        'is_active',
    ];

    public function authorization()
    {
        return $this->belongsTo(HREmployeeAuthorization::class, 'employee_authorization_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(HRDepartment::class, 'department_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(HREmployee::class, 'employee_id', 'id');
    }
}
