<?php

namespace Modules\Stock\Transformers\StockAdjustment;

use Illuminate\Http\Resources\Json\Resource;
use Modules\HumanResource\Transformers\Employee\FindHREmployeeResource;

class DetailStockAdjustmentResource extends Resource
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
            'issued_by_name' => getEmployeeFullName($this->issued_by),
            'issued_detail' => getEmployeeDescription($this->issued_by),
            'description' => $this->description,
            'approvals_status' => $this->approvals_status,
            'approvals_date' => $this->approvals_date,
            'approvals_by' => $this->approvals_by,
            'approvals_by_name' => getEmployeeFullName($this->approvals_by),
            'approvals_detail' => getEmployeeDescription($this->approvals_by),
            'approvals_note' => $this->approvals_note,
            'release_date' => $this->release_date,
            'release_by' => $this->release_by,
            'release_employee' => new FindHREmployeeResource($this->employeeReleaseBy),
            'release_detail' => getEmployeeDescription($this->release_by),
            'release_note' => $this->release_note,
            'items' => DetailStockAdjustmentItemsResource::collection($this->stockAdjustmentItem->where('is_active', 1)),
            'total_item' => $this->total_item,
            'status' => $this->status,
            'is_active' => (boolean) $this->is_active,
        ];
    }
}
