<?php

namespace Modules\Stock\Transformers\StockAdjustment;

use Illuminate\Http\Resources\Json\Resource;

class EditStockAdjustmentResource extends Resource
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
            'code' => $this->code,
            'type' => $this->type,
            'issue_date' => $this->issue_date,
            'issued_by' => $this->issued_by,
            'description' => $this->description,
            'items' => EditStockAdjustmentItemsResource::collection($this->stockAdjustmentItem->where('is_active', 1)),
            'status' => $this->status,
            'is_active' => (boolean) $this->is_active,
        ];
    }
}
