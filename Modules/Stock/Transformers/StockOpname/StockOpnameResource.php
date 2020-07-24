<?php

namespace Modules\Stock\Transformers\StockOpname;

use Illuminate\Http\Resources\Json\Resource;
use Modules\Stock\Entities\StockAdjustmentItem;
use Modules\Stock\Transformers\FindItemInformationResource;
use Modules\Stock\Transformers\StockAdjustment\StockAdjustmentItemResource;

class StockOpnameResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $stockAdjustmentItem = StockAdjustmentItem::where('is_active', 1)->where('stock_opname_id', $this->id)->get();

        if ($this->quantity_issue==$this->quantity_adjustment) {
            $stock_desc_type = 1;
            $stock_desc      = $this->approvals_status;
        } elseif ($this->quantity_issue>$this->quantity_adjustment) {
            $stock_desc_type = 0;
            $stock_desc      = "Selisih ".($this->quantity_issue - $this->quantity_adjustment)." ".getUnitConversionName($this->item_unit_conversion_id);
        } else {
            $stock_desc_type = 0;
            $stock_desc      = "";
        }
        return [
            'id' => $this->id,
            'stock_opname_group_id' => $this->stock_opname_group_id,
            'date_opname' => $this->issue_date,
            'item' => new FindItemInformationResource($this->item),
            'item_code' =>  $this->item_code,
            'item_detail' =>  getItemName($this->item_code),
            'quantity_prev' => $this->quantity_prev,
            'quantity_new' => $this->quantity_new,
            'quantity_issue' => $this->quantity_issue,
            'quantity_adjustment' => $this->quantity_adjustment,
            'item_unit_conversion_id' => ['id' => $this->item_unit_conversion_id, 'conversion_symbol' => getUnitConversionName($this->item_unit_conversion_id)],
            'note' => $this->note,
            'issued_by_name' => getEmployeeFullName($this->issued_by),
            'approvals_status' => $this->approvals_status,
            'approvals_by_name' => getEmployeeFullName($this->approvals_by),
            'status' => $this->status,
            'stock_status' => $this->stock_status,
            'data_status' => $this->data_status,
            'stock_desc_type' => $stock_desc_type,
            'stock_desc' => $stock_desc,
            'stockAdjustmentItem' => StockAdjustmentItemResource::collection($stockAdjustmentItem),
            'url_edit' => route('stock.opname.update', [$this->id]),
            'url_edit_adjustment' => route('stock.opname.update.adjustment', [$this->id]),
            'url_delete' => route('ajax.stock.destroy.stock.opname', [$this->id]),
        ];
    }
}
