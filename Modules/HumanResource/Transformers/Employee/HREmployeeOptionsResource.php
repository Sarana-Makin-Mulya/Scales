<?php

namespace Modules\HumanResource\Transformers\Employee;

use Illuminate\Http\Resources\Json\Resource;

class HREmployeeOptionsResource extends Resource
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
            'nik' => trim($this->nik),
            'detail' => trim($this->nik.', '.trim($this->name).' ('.trim(getHRDepartmentName($this->department_id).')')),
            'name' => trim($this->name),
        ];
    }
}
