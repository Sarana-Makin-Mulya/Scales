<?php

namespace Modules\General\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class UnitResource extends Resource
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
            'symbol' => $this->symbol,
            'description' => $this->description,
            'status' => (boolean) $this->is_active,
            'url_edit' => route('general.unit.update', [$this->id]),
            'url_delete' => route('ajax.general.destroy.unit', [$this->id]),
            'url_status_update' => route('general.unit.update.status', [$this->id]),
        ];
    }
}
