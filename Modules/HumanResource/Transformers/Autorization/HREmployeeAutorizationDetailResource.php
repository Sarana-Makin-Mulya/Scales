<?php

namespace Modules\HumanResource\Transformers\Autorization;

use Illuminate\Http\Resources\Json\Resource;
use Modules\HumanResource\Entities\HREmployee;
use Modules\HumanResource\Transformers\Employee\HREmployeeOptionsResource;

class HREmployeeAutorizationDetailResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'department_id' => trim($this->department_id),
            'employeeOptions' => HREmployeeOptionsResource::collection($this->getEmployeeOptions($this->department_id)),
            'employee_nik' => trim($this->employee_nik),
            'employee_name' => getEmployeeFullName($this->employee_nik),
            'department_name' => getHRDepartmentName($this->department_id),
            'status' => $this->is_active,
        ];
    }

    public function getEmployeeOptions($department_id)
    {
        return HREmployee::where('department_id', $department_id)->get();
    }
}
