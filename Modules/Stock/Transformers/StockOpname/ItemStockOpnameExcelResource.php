<?php

namespace Modules\Stock\Transformers\StockOpname;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Stock\Entities\ItemUnitConversion;

class ItemStockOpnameExcelResource extends Resource
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
            'item_category' => getCategoryName($this->item_category_id),
            'item_brand' => getBrandName($this->item_brand_id),
            'rack_location' => getItemLocation($this->code),
            'name' => $this->name,
            'detail' => $this->detail,
            'type' => $this->type,
            'size' => $this->size,
            'color' => $this->color,
            'description' => $this->description,
            'current_stock' => $this->current_stock,
            'new_stock' => null,
            'satuan' => $unit_name,
        ];
    }
}
