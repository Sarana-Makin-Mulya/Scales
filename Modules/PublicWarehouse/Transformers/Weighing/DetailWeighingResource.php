<?php

namespace Modules\PublicWarehouse\Transformers\Weighing;

use Illuminate\Http\Resources\Json\Resource;
use Modules\PublicWarehouse\Entities\Weighing;
use Modules\PurchaseOrder\Entities\JunkItemSpk;
use Modules\PurchaseOrder\Entities\JunkItemSpkDetail;
use Modules\PurchaseOrder\Entities\PurchaseOrder;
use Modules\PurchaseOrder\Entities\PurchaseOrderItems;

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
            'item_name' => $this->getWeighingItem($this->id),
            'junk_item_spk_code_text' => $this->getSPKcode($this->junk_item_spk_code),
            'junk_item_spk_detail_text' => $this->getSPKDetail($this->junk_item_spk_detail_id),
            'purchase_order_code_text' => $this->getPOCode($this->purchase_order_code),
            'purchasing_purchase_order_item_text' => $this->getPOItem($this->purchasing_purchase_order_item_id),
            'weighing_item_code_text' => getWeighingItemName($this->weighing_item_code),

            'weighing_category_id' => $this->weighing_category_id,
            'weighing_category_name' => getWeighingCategoryName($this->weighing_category_id),
            'junk_item_spk_code' => $this->junk_item_spk_code,
            'junk_item_spk_detail_id' => $this->junk_item_spk_detail_id,
            'purchase_order_code' => $this->purchase_order_code,
            'purchasing_purchase_order_item_id' => $this->purchasing_purchase_order_item_id,
            'weighing_item_code' => $this->weighing_item_code,
            'do_code' => $this->do_code,
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
            'second_weight' => ($this->second_weight==null) ? 0 : $this->second_weight,
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

    public function getWeighingItem($id)
    {
        $item_name = null;
        $weighing = Weighing::query()
                    ->where('id', $id)
                    ->first();

        if (!empty($weighing)) {
            if ($weighing->weighing_category_id==1) {
                $data = JunkItemSpkDetail::query()
                    ->where('id', $weighing->junk_item_spk_detail_id)
                    ->first();
                $item_name = (!empty($data)) ? getJunkItemName($data->junk_item_code) : 1;
            } elseif ($weighing->weighing_category_id==2) {
                $data = PurchaseOrderItems::query()
                    ->where('id', $weighing->purchasing_purchase_order_item_id)
                    ->first();
                $item_name = (!empty($data)) ? getItemName($data->item_code) : 2;
            } else {
                $data = WeighingItems::query()
                    ->where('code', $weighing->weighing_item_code)
                    ->first();
                $item_name = (!empty($data)) ? $data->name: 3;
            }
        }

        return $item_name;
    }

    public function getSPKcode($code)
    {
        $data = JunkItemSpk::query()
            ->where('code', $code)
            ->first();

        if (!empty($data)) {
            $issue_date = date('d/m/Y', strtotime($data->issue_date));
            $buyer_name = ($data->buyer_type==1) ? getEmployeeDescription($data->buyer_nik) : $data->buyer_name." (External)";
            return preg_replace('/[\s]+/mu', ' ',$data->code." - ".$issue_date." - ".$buyer_name);
        } else {
            return null;
        }
    }

    public function getSPKDetail($id)
    {
        $data = JunkItemSpkDetail::query()
            ->where('id', $id)
            ->first();

        if (!empty($data)) {
            return getJunkItemName($data->junk_item_code);
        } else {
            return null;
        }
    }

    public function getPOCode($code)
    {
        $data = PurchaseOrder::query()
            ->where('code', $code)
            ->first();

        if (!empty($data)) {
            $issue_date = date('d/m/Y', strtotime($this->issue_date));
            return $data->code." - ".$issue_date." - ".$data->supplier_name;
        } else {
            return null;
        }
    }

    public function getPOItem($id)
    {
        $data = PurchaseOrderItems::query()
            ->where('id', $id)
            ->first();

        if (!empty($data)) {
            return getItemPreview($data->item_code)." (".$data->quantity." ".getUnitConversionName($data->item_unit_conversion_id).")";
        } else {
            return null;
        }
    }


}
