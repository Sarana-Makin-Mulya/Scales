<?php

namespace Modules\General\Transformers\KPI\Detail;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Modules\PublicWarehouse\Entities\GoodsRequestItemOut;
use Modules\PublicWarehouse\Entities\GoodsRequestItems;

class KPIWarehouseGoodsRequestResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $diff       = 0;
        $out_date   = GoodsRequestItemOut::query()
                        ->where('ic_goods_request_item_id',$this->id)
                        ->orderBy('out_date', 'Desc')
                        ->first();
        if ($this->status==4) {
            $tr_status = 0;
            if (!empty($out_date->out_date)) {
                $B_date = Carbon::parse($this->goodsRequest->transaction_date);
                $E_date = Carbon::parse($out_date->out_date);
                $diff   = $B_date->diffInDays($E_date);
                if ($diff<=7) {
                    $tr_status = 0;
                } else {
                    $tr_status = 1;
                }
            }
        } else {
            $tr_status = 1;
        }


        return [
            'code' => $this->ic_goods_request_code,
            'goods_requester_detail' => getEmployeeFullName($this->goodsRequest->goods_requester),
            'transaction_date' => $this->goodsRequest->transaction_date,
            'item_detail' => getItemDetail($this->item_code),
            'qty_request' => $this->quantity,
            'qty_out' => $this->quantity_out,
            'out_date' => (!empty($out_date->out_date)) ? $out_date->out_date : '-',
            'unit_name' => getUnitConversionName($this->item_unit_conversion_id),
            'goods_submitter_name' => getEmployeeFullName($this->goodsRequest->goods_submitter),
            'status' => $this->status,
            'tr_status' => $tr_status,
            'diff' => $diff
        ];

    }
}
