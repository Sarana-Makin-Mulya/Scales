<?php

namespace Modules\PublicWarehouse\Transformers\Weighing;

use Illuminate\Http\Resources\Json\Resource;

class DetailWeighingResource extends Resource
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
            'weighing_category_name' => getWeighingCategoryName($this->weighing_category_id),
            'junk_item_request_code' =>$this->junk_item_request_code,
            'junk_item_request_detail_id' =>$this->junk_item_request_detail_id,
            'purchase_order_code' =>$this->purchase_order_code,
            'purchasing_purchase_order_item_id' =>$this->purchasing_purchase_order_item_id,
            'weighing_item_code' =>$this->weighing_item_code,
            'do_code' =>$this->do_code,
            'first_operator_by' => $this->first_operator_by,
            'first_operator_by_name' => getEmployeeFullName($this->first_operator_by),
            'second_operator_by' => $this->second_operator_by,
            'second_operator_by_name' => getEmployeeFullName($this->second_operator_by),
            'receiper' => $this->receiper,
            'driver_name' => $this->driver_name,
            'supplier_code' => $this->supplier_code,
            'supplier_name' => $this->supplier_name,
            'first_number_plate' => $this->first_number_plate,
            'second_number_plate' => $this->second_number_plate,
            'first_datetime' => $this->first_datetime,
            'second_datetime' => $this->second_datetime,
            'first_weight' => $this->first_weight,
            'second_weight' => $this->second_weight,
            'tolerance_weight' => $this->tolerance_weight,
            'tolerance_reason' => $this->tolerance_reason,
            'netto_weight' => getNettoWeight($this->id),
            'file' => str_replace('uploads/weighing/','',$this->file),
            'issue_date' => $this->issue_date,
            'issued_by_name' => getEmployeeFullName($this->issued_by),
            'input_type' =>$this->input_type,
            'stage' =>$this->stage,
            'status' =>$this->status,
            'url_download' => route('po.weighing.download'),
        ];
    }
}
