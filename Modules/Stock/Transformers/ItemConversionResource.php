<?php

namespace Modules\Stock\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ItemConversionResource extends Resource
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
            'item_code' => $this->item_code,
            'conversion_unit' => $this->unit_id,
            'conversion_value' => $this->conversion_value,
            'conversion_symbol' => getUnitName($this->unit_id),
            'primary' => (boolean) $this->is_primary,
            'status' => (boolean) $this->is_active,
        ];
    }
}
