<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HRDepartmentLevel extends Model
{
    use SoftDeletes;

    protected $table = 'hrd_department_levels';

    protected $fillable = ['code','name', 'description','is_active'];

    public function departments()
    {
        return $this->hasMany(HRDepartment::class);
    }
}
