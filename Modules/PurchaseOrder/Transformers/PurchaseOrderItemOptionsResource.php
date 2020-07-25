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
        return [
            'id' => $this->id,
            'purchasing_purchase_order_code' => $this->purchasing_purchase_order_code,
            'item_code' => $this->item_code,
            'item_dev' => getItemName($this->item_code),
            'detail' => getItemPreview($this->item_code)." (".$this->quantity." ".getUnitConversionName($this->item_unit_conversion_id).")",
        ];
    }
}
