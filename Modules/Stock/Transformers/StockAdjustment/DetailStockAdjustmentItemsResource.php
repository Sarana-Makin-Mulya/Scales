<?php

namespace Modules\Stock\Transformers\StockAdjustment;

use Illuminate\Http\Resources\Json\Resource;

class DetailStockAdjustmentItemsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
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
            'stock_adjustment_code' => $this->stock_adjustment_code,
            'item_code' => $this->item_code,
            'item_description' => getItemDetail($this->item_code),
            'item_unit_conversion_id' => $this->item_unit_conversion_id,
            'item_unit_conversion' => getUnitConversionName($this->item_unit_conversion_id),
            'quantity' => $this->quantity,
            'stock_adjustment_category_id' => $this->stock_adjustment_category_id,
            'stock_adjustment_category' => getStockAdjustmentCategoryName($this->stock_adjustment_category_id),
            'description' => $this->description,
            'stock_status_border' => $stock_status_border,
            'stock_status_icon' => $stock_status_icon,
            'stock_status_color' => $stock_status_color,
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
        ];
    }
}
