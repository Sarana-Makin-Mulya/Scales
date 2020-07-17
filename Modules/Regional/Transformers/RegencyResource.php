<?php

namespace Modules\Regional\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class RegencyResource extends Resource
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
            'province_id' => $this->province_id,
            'sort_name' => $this->sort_name,
        ];
    }
}
