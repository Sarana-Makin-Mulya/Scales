<?php

namespace Modules\Stock\Transformers\Item;

use Illuminate\Http\Resources\Json\Resource;

class ItemSizeOptionResource extends Resource
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
            'id' => $this->size,
            'name' => $this->size,
        ];
    }
}
