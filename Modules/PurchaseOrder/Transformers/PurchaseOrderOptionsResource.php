<?php

namespace Modules\PurchaseOrder\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PurchaseOrderOptionsResource extends Resource
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

        return [
            'code' => $this->code,
            'supplier_code' => $this->supplier_code,
            'supplier_name' => $this->supplier_name,
            'detail' => $this->code." - ".$issue_date." - ".$this->supplier_name,
        ];
    }
}
