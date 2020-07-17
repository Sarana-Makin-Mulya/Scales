<?php

namespace Modules\General\Transformers\Machine;

use Illuminate\Http\Resources\Json\Resource;

class MachineOptionsResource extends Resource
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
            'name' => $this->name,
            'serial_number' => $this->serial_number,
            'capacity' => $this->capacity,
            'detail' => $this->name." ".$this->serial_number." ".$this->capacity,
        ];
    }
}
