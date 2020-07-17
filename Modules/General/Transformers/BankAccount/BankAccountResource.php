<?php

namespace Modules\General\Transformers\BankAccount;

use Illuminate\Http\Resources\Json\Resource;

class BankAccountResource extends Resource
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
            'supplier_code' => $this->supplier_code,
            'supplier_name' => ($this->account_data=="supplier") ? getSupplierName($this->supplier_code) : 'SMM Internal',
            'bank_id' => $this->bank_id,
            'bank_name' => getBankName($this->bank_id),
            'currency_id' => $this->currency_id,
            'currency_name' => getCurrencyName($this->currency_id),
            'account_data' => $this->account_data,
            'account_type' => $this->account_type,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'kcp_name' => $this->kcp_name,
            'kcp_address' => $this->kcp_address,
            'description' => $this->description,
            'is_primary' => $this->is_primary,
            'status' => (boolean) $this->is_active,
            'url_edit' => route('general.bank.account.update', [$this->id]),
            'url_delete' => route('ajax.general.destroy.bank.account', [$this->id]),
            'url_status_update' => route('general.bank.account.update.status', [$this->id]),
        ];
    }
}
