<?php

namespace Modules\HumanResource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HRDepartment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'hrd_departments';

    protected $fillable = ['department_level_id','code','name', 'description','is_active'];

    public function employees()
    {
        return $this->hasMany(HREmployee::class);
    }

    public function departmentLevel()
    {
        return $this->belongsTo(HRDepartmentLevel::class);
    }

    public function parent()
    {
        return $this->belongsTo(HRDepartment::class, 'parent_id')->with('parent');
    }

    public function children()
    {
        return $this->hasMany(HRDepartment::class, 'parent_id')->with('children');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
