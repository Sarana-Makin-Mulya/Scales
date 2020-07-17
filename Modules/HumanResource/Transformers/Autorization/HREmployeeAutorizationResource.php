<?php

namespace Modules\HumanResource\Transformers\Autorization;

use Illuminate\Http\Resources\Json\Resource;

class HREmployeeAutorizationResource extends Resource
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
            'code' =>$this->code,
            'name' =>$this->name,
            'description' =>$this->description,
            'url_update' =>$this->description,
            'description' =>$this->description,
            'details' => HREmployeeAutorizationDetailResource::collection($this->detail->where('is_active', 1)),
            'url_edit' => route('hr.autorization.update', [$this->id]),
            'url_delete' => route('ajax.hr.destroy.autorization', [$this->id]),

        ];
    }
}
