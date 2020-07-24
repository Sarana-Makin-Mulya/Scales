<?php

namespace Modules\Stock\Transformers\Item;

use Illuminate\Http\Resources\Json\Resource;

class DetailItemStockOldAppResource extends Resource
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
            'item_old_code' => $this->item_old_code,
            'item_code' => $this->item_code,
            'name' => $this->name,
            'size' => $this->size,
            'tipe' => $this->tipe,
            'brand' => $this->brand,
            'color' => $this->color,
            'moq' => $this->moq,
            'qty_borrow' => $this->qty_borrow,
            'qty_stock' => $this->qty_stock,
            'unit_name' => $this->unit_name,
        ];
    }
}
