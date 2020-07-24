<?php

namespace Modules\Stock\Transformers\Item;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Stock\Entities\ItemUnitConversion;
use Modules\Stock\Transformers\ItemConversionResource;

class DetailItemResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $unitPrimary = ItemUnitConversion::where('item_code',$this->code)->where('is_active',1)->where('is_primary',1)->first();
        $unit_id = (!empty($unitPrimary)) ? $unitPrimary->unit_id : '';
        $unit_name = (!empty($unitPrimary)) ? getUnitName($unitPrimary->unit_id) : '';

        return [
            'code' => $this->code,
            'old_code' => $this->old_code,
            'item_category' => getCategoryName($this->item_category_id),
            'item_brand' => getBrandName($this->item_brand_id),
            'name' => $this->name,
            'detail' => $this->detail,
            'slug' => $this->slug,
            'nickname' => $this->nickname,
            'type' => $this->type,
            'size' => $this->size,
            'color' => $this->color,
            'description' => $this->description,
            'is_priority' => $this->is_priority,
            'borrowable' => $this->borrowable,
            'max_stock' => $this->max_stock,
            'min_stock' => $this->min_stock,
            'location' => getItemLocation($this->code),
            'current_stock' => getItemStock($this->code),
            'status' => (boolean) $this->is_active,
            'unit_name' => $unit_name,
            'item_conversion' => ItemConversionResource::collection(ItemUnitConversion::where('item_code',$this->code)->where('is_active',1)->where('is_primary',0)->get()),
        ];
    }
}
