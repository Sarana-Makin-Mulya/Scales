<?php
namespace Modules\Stock\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Stock\Entities\ItemUnitConversion;

class FindItemInformationResource extends Resource
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
            'detail' => $this->code." - ".$this->detail,
            'stock' => getItemStock($this->code),
            'rack_location' => getItemLocation($this->code),
            'item_unit_conversion_id' => getUnitConversionId($this->code),
            'item_conversion' => ItemConversionResource::collection($item_conversion),
        ];
    }
}
