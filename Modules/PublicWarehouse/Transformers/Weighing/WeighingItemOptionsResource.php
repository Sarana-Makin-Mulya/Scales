<?php

namespace Modules\PublicWarehouse\Transformers\Weighing;

use Illuminate\Http\Resources\Json\Resource;

class WeighingItemOptionsResource extends Resource
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
            'code' => $this->code,
            'name' => $this->name,
            'description' =>$this->description,
        ];
    }
}
