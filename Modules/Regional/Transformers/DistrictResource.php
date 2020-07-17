<?php

namespace Modules\Regional\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class DistrictResource extends Resource
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
            'regency_id' => $this->regency_id,
            'name' => $this->name,
        ];
    }
}
