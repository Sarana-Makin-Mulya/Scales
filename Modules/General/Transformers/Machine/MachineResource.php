<?php

namespace Modules\General\Transformers\Machine;

use Illuminate\Http\Resources\Json\Resource;

class MachineResource extends Resource
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
            'description' => $this->description,
            'status' => (boolean) $this->is_active,
            'url_edit' => route('general.machine.update', [$this->id]),
            'url_delete' => route('ajax.general.destroy.machine', [$this->id]),
            'url_status_update' => route('general.machine.update.status', [$this->id]),
        ];
    }
}
