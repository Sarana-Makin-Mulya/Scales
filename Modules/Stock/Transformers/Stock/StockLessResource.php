<?php

namespace Modules\Stock\Transformers\Stock;

use Illuminate\Http\Resources\Json\Resource;
use Modules\PurchaseOrder\Entities\PurchaseRequestItemDetail;
use Modules\Stock\Entities\ItemUnitConversion;
use Modules\Stock\Transformers\FindItemResource;
use Modules\Stock\Transformers\ItemCategoryResource;
use Modules\Stock\Transformers\ItemConversionResource;

class StockLessResource extends Resource
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

        $itemUnitConversion = ItemUnitConversion::query()
            ->where('item_code',$this->code)
            ->where('is_active',1)
            ->orderBy('is_primary', 'Desc')
            ->get();

        $quantity = $this->max_stock;
        if ($quantity<0) {
            $quantity = 0;
        }

        return [
            'code' => $this->code,
            'input_type' => 'stock-less',
            'pr_items' => new FindItemResource($this),
            'item_category' =>  getCategoryName($this->item_category_id),
            'name' => $this->name,
            'item_detail' => $this->detail,
            'max_stock' => $this->max_stock,
            'min_stock' => $this->min_stock,
            'stock_min' => $this->min_stock,
            'stock_last' => getItemStock($this->code),
            'status' => (boolean) $this->is_active,
            'stock' => getItemStock($this->code),
            'rack_location' => getItemLocation($this->code),
            'item_unit_conversion_id' => getUnitConversionId($this->code),
            'unitConversionOptions' => ItemConversionResource::collection($itemUnitConversion),
            'description' => null,
            'quantity' => $quantity,
            'priority' => 0,
            'is_priority' => 0,
            'target_arrival_date' => date('Y-m-d'),
            'unit_id' => $unit_id,
            'unit_name' => $unit_name,
            'detail' => [],
            'checked' => $this->checkPOItemDetail($this->pr_code,$this->code),
        ];
    }

    public function checkPOItemDetail($pr_code, $item_code)
    {
        $detail = PurchaseRequestItemDetail::query()
            ->select('ic_purchase_request_item_details.*')
            ->leftjoin('ic_purchase_request_items','ic_purchase_request_items.id','=','ic_purchase_request_item_details.ic_purchase_request_item_id')
            ->where('ic_purchase_request_items.ic_purchase_request_code',$pr_code)
            ->where('ic_purchase_request_item_details.item_code', $item_code)
            ->where('ic_purchase_request_item_details.is_active',1)
            ->where('ic_purchase_request_item_details.source','stock-less')
            ->first();

        return !empty($detail) ? 1 : 0;
    }
}
