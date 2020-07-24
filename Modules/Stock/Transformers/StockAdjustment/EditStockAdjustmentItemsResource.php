<?php

namespace Modules\Stock\Transformers\StockAdjustment;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Stock\Entities\Item;
use Modules\Stock\Entities\ItemUnitConversion;
use Modules\Stock\Transformers\ItemConversionResource;

class EditStockAdjustmentItemsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $items = Item::select('code','detail')
            ->where('code',$this->item_code)
            ->first();

        $item_conversion = ItemUnitConversion::query()
            ->where('item_code',$this->item_code)
            ->where('is_active',1)
            ->orderBy('is_primary','desc')
            ->get();

        $unit = ItemUnitConversion::select('id','unit_id')
            ->where('id',$this->item_unit_conversion_id)
            ->first();
        $item_unit_conversion_id = ['id' => $this->item_unit_conversion_id, 'conversion_symbol' => getUnitName($unit->unit_id)];

        $stock_status = getStockAdjustmentCategoryStock($this->stock_adjustment_category_id);
        if ($stock_status==1) {
            $stock_status_border = "border-success";
            $stock_status_icon = "fas fa-plus";
            $stock_status_color = "#38c172";
        } else {
            $stock_status_border = "border-danger";
            $stock_status_icon = "fas fa-minus";
            $stock_status_color = "#e3342f";
        }
        return [
            'id' => $this->id,
            'item_code' => $items,
            'item_detail' => getItemDetail($this->item_code),
            'stock' => getItemStock($this->item_code),
            'unitConversionOptions' => ItemConversionResource::collection($item_conversion),
            'item_unit_conversion_id' => $item_unit_conversion_id,
            'quantity' => $this->quantity,
            'stock_adjustment_category_id' => ['id' => $this->stock_adjustment_category_id, 'name' => getStockAdjustmentCategoryName($this->stock_adjustment_category_id), 'stock' => $stock_status],
            'description' => $this->description,
            'stock_status_border' => $stock_status_border,
            'stock_status_icon' => $stock_status_icon,
            'stock_status_color' => $stock_status_color
        ];
    }
}
