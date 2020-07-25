<?php

namespace Modules\PublicWarehouse\Transformers\Weighing;

use Illuminate\Http\Resources\Json\Resource;
use Modules\PublicWarehouse\Entities\Weighing;
use Modules\PublicWarehouse\Entities\WeighingItems;
use Modules\PurchaseOrder\Entities\JunkItemSpkDetail;
use Modules\PurchaseOrder\Entities\PurchaseOrderItems;

class WeighingFirstOptionsResource extends Resource
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
            // 'detail' => $this->getWeighingItem($this->id)." (".date('d/m/Y', strtotime($this->first_datetime)).", ".$this->first_number_plate.", ".$this->driver_name.", ".$this->supplier_name.")",
            'detail' => $this->getWeighingItem($this->id)." (".$this->first_number_plate.", ".date('d/m/Y', strtotime($this->first_datetime)).")",
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
}
