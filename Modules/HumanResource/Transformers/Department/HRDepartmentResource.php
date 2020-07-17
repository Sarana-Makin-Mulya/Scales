<?php

namespace Modules\HumanResource\Transformers\Department;

use Illuminate\Http\Resources\Json\Resource;

class HRDepartmentResource extends Resource
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
            'id' => trim($this->id),
            'name' => trim($this->name),
        ];
    }
}
