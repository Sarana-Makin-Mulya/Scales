<?php

namespace Modules\General\Transformers\Log;

use Illuminate\Http\Resources\Json\Resource;

class LogActivityGroupByEmloyeeResource extends Resource
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
            'value' => $this->causer_id,
            'text' => getEmployeeFullName(getAuthEmployeeNik($this->causer_id)),
        ];
    }
}
