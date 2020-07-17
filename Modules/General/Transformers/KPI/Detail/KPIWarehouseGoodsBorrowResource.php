<?php

namespace Modules\General\Transformers\KPI\Detail;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class KPIWarehouseGoodsBorrowResource extends Resource
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
        $borrow_day = $this->goodsBorrow->borrow_day + $this->goodsBorrow->extended_day;
        if ($this->return_status==1) {
            $tr_status = 0;
            if (!empty($this->return_date)) {
                $B_date = Carbon::parse($this->goodsBorrow->transaction_date);
                $E_date = Carbon::parse($this->return_date);
                $diff   = $B_date->diffInDays($E_date)+1;
                if ($diff<=$borrow_day) {
                    $tr_status = 0;
                } else {
                    $tr_status = 1;
                }
            }
        } else {
            $tr_status = 1;
        }

        return [
            'code' => $this->goods_borrow_code,
            'goods_borrower_detail' => getEmployeeFullName($this->goodsBorrow->borrower),
            'transaction_date' => $this->goodsBorrow->transaction_date,
            'item_detail' => getItemDetail($this->item_code),
            'qty' => $this->quantity,
            'borrow_day' => $borrow_day,
            'target_return_date' => $this->goodsBorrow->target_return_date,
            'return_date' => (!empty($this->return_date)) ? $this->return_date : '-',
            'unit_name' => getUnitConversionName($this->item_unit_conversion_id),
            'goods_submitter_name' => getEmployeeFullName($this->goodsBorrow->borrow_submitter),
            'status' => $this->return_status,
            'tr_status' => $tr_status,
            'diff' => $diff
        ];
    }
}
