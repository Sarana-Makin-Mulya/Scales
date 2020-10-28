<?php

namespace Modules\PurchaseOrder\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PurchaseOrderItemOptionsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        if (!empty($this->item_detail)) {
            $item_detail = $this->item_detail;
        } else {
            $item_detail = $this->items->code." - ".$this->items->detail;
        }

        if (!empty($this->item_unit)) {
            $item_unit = $this->item_unit;
        } else {
            $item_unit = getUnitConversionName($this->item_unit_conversion_id);
        }

        return [
            'id' => $this->id,
            'purchasing_purchase_order_code' => $this->purchasing_purchase_order_code,
            'item_code' => $this->item_code,
            'item_dev' => $item_detail,
            'detail' => $item_detail." (".$this->quantity." ".$item_unit.")",
        ];
    }
}
