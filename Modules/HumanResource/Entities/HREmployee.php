<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HREmployee extends Model
{
    use SoftDeletes;

    const GENDER_MAN   = 1;
    const GENDER_WOMAN = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'hrd_employees';

    protected $fillable = [
        'department_id',
        'position_id',
        'religion_id',
        'blood_type_id',
        'salary_type_id',
        'ptkp_code',
        'payroll_type_id',
        'marital_status_id',
        'employee_status_id',
        'company_id',
        'office_hour_id',
        'nik',
        'old_nik',
        'ktp',
        'kk',
        'npwp',
        'sim',
        'name',
        'email',
        'phone',
        'address',
        'photo',
        'gender',
        'birth_place',
        'birth_at',
        'join_at',
        'leave_at',
        'is_active',
    ];

    // public function contacts()
    // {
    //     return $this->hasMany(EmployeeContact::class);
    // }

    public function department()
    {
        return $this->belongsTo(HRDepartment::class);
    }

    // public function payrollType()
    // {
    //     return $this->belongsTo(PayrollType::class);
    // }

    // public function salaryType()
    // {
    //     return $this->belongsTo(SalaryType::class);
    // }

    // public function businessTrip()
    // {
    //     return $this->hasMany(BusinessTrip::class);
    // }

    // public function overtimeEmployee()
    // {
    //     return $this->hasMany(OvertimeEmployee::class);
    // }

    // public function cooperative()
    // {
    //     return $this->hasMany(Cooperative::class);
    // }

    // public function loan()
    // {
    //     return $this->hasMany(Loan::class);
    // }
}
