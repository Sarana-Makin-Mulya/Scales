<?php

namespace Modules\PurchaseOrder\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class JunkItemSpkOptionsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
       $issue_date = date('d/m/Y', strtotime($this->issue_date));
       $buyer_name = ($this->buyer_type==1) ? getEmployeeDescription($this->buyer_nik) : $this->buyer_name." (External)";
        return [
            'code' => $this->code,
            'buyer_code' => $this->buyer_code,
            'buyer_name' => ($this->buyer_type==1) ? getEmployeeFullName($this->buyer_nik): $this->buyer_name,
            'buyer_pic' => ($this->buyer_type==1) ? getEmployeeFullName($this->buyer_nik): $this->buyer_pic,
            'detail' => $this->code." - ".$issue_date." - ".$buyer_name,
        ];
    }
}
