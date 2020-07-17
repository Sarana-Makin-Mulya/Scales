<?php

namespace Modules\General\Transformers\BankAccount;

use Illuminate\Http\Resources\Json\Resource;

class BankAccountOptionsResource extends Resource
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
            'name' => getBankName($this->bank_id)."(".getCurrencySymbol($this->currency_id).") : ".$this->account_number." a.n ".$this->account_name."",
        ];
    }
}
