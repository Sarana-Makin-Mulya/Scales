<?php

namespace Modules\Regional\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class VillageResource extends Resource
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
            'district_id' => $this->district_id,
            'name' => $this->name,
        ];
    }
}
