<?php

namespace Modules\Stock\Transformers\StockQuarantine;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Stock\Entities\StockAdjustmentCategory;
use Modules\Stock\Transformers\FindItemResource;

class StockQuarantineResource extends Resource
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
            'item' => new FindItemResource($this->item),
            'stock_transaction_id' => $this->stock_transaction_id,
            'stock_quarantine_id' => $this->id,
            'item_code' => $this->item_code,
            'item_unit_conversion_id' => $this->item_unit_conversion_id,
            'quantity' => $this->quantity,
            'stock_adjustment_category_id' => 5, // ID Dead Stock on Stock Adjustment Category
            'stock_out' => getStockTransaction($this->stock_transaction_id, 'out'),
            'stock_current' => getStockTransaction($this->stock_transaction_id, 'current'),
            'item_unit_conversion' => getUnitConversionName($this->item_unit_conversion_id),
            'action' => ($this->action==2) ? 'Dead Stock' : 'Quarantine',
            'action_date' => $this->action_date,
            'issue_date' => $this->issue_date,
            'issued_by' => $this->issued_by,
            'reason' => $this->reason,
            'approvals' => $this->approvals,
            'status' => $this->status,
            'data_status' => $this->data_status,
        ];
    }
}
