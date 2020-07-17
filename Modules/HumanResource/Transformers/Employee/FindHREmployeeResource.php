<?php

namespace Modules\HumanResource\Transformers\Employee;

use Illuminate\Http\Resources\Json\Resource;

class FindHREmployeeResource extends Resource
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
            'identity' => trim($this->nik.', '.$this->name.' ('.trim(getHRDepartmentName($this->department_id).')')),
            'nik' => trim($this->nik),
            'name' => trim($this->name),
            'departement_code' => trim(getHRDepartmentCode($this->department_id)),
            'departement' => trim(getHRDepartmentName($this->department_id)),
        ];
    }
}
