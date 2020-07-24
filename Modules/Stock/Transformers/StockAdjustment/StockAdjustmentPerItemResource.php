<?php

namespace Modules\Stock\Transformers\StockAdjustment;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Stock\Entities\ItemUnitConversion;
use Modules\Stock\Transformers\ItemConversionResource;

class StockAdjustmentPerItemResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

       if (!empty($this->stockAdjustment)) {
            if ($this->stockAdjustment->type==3) {
                $process_category = "Dead Stock";
            } elseif ($this->stockAdjustment->type==2) {
                $process_category = "Stok Opname";
            } else {
                $process_category = "Penyesuaian Stock";
            }
        } else {
            $process_category = "Penyesuaian Stock";
        }

        return [
            'id' => $this->id,
            'stock_opname_id' => $this->stock_opname_id,
            'stockOpname' => $this->stockOpname,
            'stockOpnameGroup' => $this->stockOpnameGroup,
            'adjustment' => $this->stockAdjustment,
            'process_category' => $process_category,
            'stock_adjustment_code' => $this->stock_adjustment_code,
            'item_code' => $this->item_code,
            'item_description' => getItemName($this->item_code),
            'quantity' => $this->quantity,
            'item_unit_conversion' => getUnitConversionName($this->item_unit_conversion_id),
            'stock_adjustment_category' => getStockAdjustmentCategoryName($this->stock_adjustment_category_id),
            'description' => $this->description,
            'issue_date' => $this->issue_date,
            'issued_by' => $this->issued_by,
            'issued_by_name' => getEmployeeFullName($this->issued_by),
            'approvals_status' => $this->approvals_status,
            'approvals_date' => $this->approvals_date,
            'approvals_by' => $this->approvals_by,
            'approvals_by_name' => getEmployeeFullName($this->approvals_by),
            'approvals_note' => $this->approvals_note,
            'release_date' => $this->release_date,
            'release_by' => $this->release_by,
            'release_by_name' => getEmployeeFullName($this->release_by),
            'release_note' => $this->release_note,
            'status' => $this->status,
            'data_status' => $this->data_status,
            'url_approvals_per_item' => route('stock.adjustment.approvals.per.item.update', [$this->id]),
        ];
    }
}
