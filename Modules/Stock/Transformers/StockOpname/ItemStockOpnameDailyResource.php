<?php

namespace Modules\Stock\Transformers\StockOpname;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Stock\Entities\ItemUnitConversion;

class ItemStockOpnameDailyResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $unitPrimary = ItemUnitConversion::where('item_code',$this->item_code)->where('is_active',1)->where('is_primary',1)->first();
        $unit_id = (!empty($unitPrimary)) ? $unitPrimary->unit_id : '';
        $unit_name = (!empty($unitPrimary)) ? getUnitName($unit_id) : '';

        return [
            'id' => $this->id,
            'code' => $this->item_code,
            'name' => getItemName($this->item_code),
            'item_category' => getItemCategory($this->item_code),
            'rack_location' => getItemLocation($this->item_code),
            'satuan' => $unit_name,
            'quantity_prev' => $this->quantity_prev,
            'quantity_new' => $this->quantity_new,
            'quantity_issue' => $this->quantity_issue,
            'quantity_issue_approved' => $this->quantity_issue_approved,
            'quantity_adjustment' => $this->quantity_adjustment,
            'note' => $this->note,
            'issue_date' => $this->issue_date,
            'issued_by' => $this->issued_by,
            'approvals_status' => $this->approvals_status,
            'approvals_by' => $this->approvals_by,
            'status' => $this->status,
            'stock_status' => $this->stock_status,
            'data_status' => $this->data_status,
       ];
    }
}
