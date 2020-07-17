<?php

namespace Modules\General\Transformers\Currency;

use Illuminate\Http\Resources\Json\Resource;

class CurrencyOptionResource extends Resource
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
            'symbol' => $this->symbol
        ];
    }
}
