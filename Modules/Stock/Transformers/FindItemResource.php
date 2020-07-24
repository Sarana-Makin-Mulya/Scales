<?php

namespace Modules\Stock\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Stock\Entities\ItemUnitConversion;

class FindItemResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $item_conversion = ItemUnitConversion::query()
            ->where('item_code',$this->code)
            ->where('is_active',1)
            ->orderBy('is_primary','desc')
            ->get();

        return [
            'code' => $this->code,
            'name' => $this->detail,
            'detail' => $this->code." - ".$this->detail,
            'stock' => getItemStock($this->code),
            'rack_location' => getItemLocation($this->code),
            'item_category_id' => $this->item_category_id,
            'item_category_name' => getCategoryName($this->item_category_id),
            'item_conversion' => ItemConversionResource::collection($item_conversion),
        ];
    }
}
