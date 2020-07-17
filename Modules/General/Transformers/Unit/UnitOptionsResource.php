<?php

namespace Modules\General\Transformers\Unit;

use Illuminate\Http\Resources\Json\Resource;

class UnitOptionsResource extends Resource
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
            'detail'=> ['id' => $this->id, 'name' => $this->name, 'symbol' => $this->symbol]
        ];
    }
}
