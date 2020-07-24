<?php

namespace Modules\Stock\Transformers\StockAdjustment;

use Illuminate\Http\Resources\Json\Resource;

class StockAdjustmentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        if ($this->type==3) {
            $process_category = "Dead Stock";
        } elseif ($this->type==2) {
            $process_category = "Stok Opname";
        } else {
            $process_category = "Penyesuaian Stock";
        }

        return [
            'code' => $this->code,
            'type' => $this->type,
            'issue_date' => $this->issue_date,
            'issued_by' => $this->issued_by,
            'issued_by_name' => getEmployeeFullName($this->issued_by),
            'issued_detail' => getEmployeeDescription($this->issued_by),
            'description' => $this->description,
            'process_category' => $process_category,
            'items' => StockAdjustmentItemResource::collection($this->stockAdjustmentItem->where('is_active', 1)),
            'total_item' => $this->total_item,
            'status' => $this->status,
            'is_active' => (boolean) $this->is_active,
            'url_update' => route('stock.adjustment.update', [$this->code]),
            'url_edit' => route('ajax.stock.edit.adjustment', [$this->code]),
            'url_delete' => route('ajax.stock.destroy.adjustment', [$this->code]),
            'url_approvals' => route('stock.adjustment.approvals.update', [$this->code]),
            'url_report' => route('stock.adjustment.report', [$this->code]),
            'url_export_pdf' => route('stock.adjustment.export.pdf'),
        ];
    }
}
