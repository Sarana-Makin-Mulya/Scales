<?php

namespace Modules\General\Transformers\Bank;

use Illuminate\Http\Resources\Json\Resource;

class BankResource extends Resource
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
            'name' => $this->name,
            'description' => $this->description,
            'status' => (boolean) $this->is_active,
            'url_edit' => route('general.bank.update', [$this->id]),
            'url_delete' => route('ajax.general.destroy.bank', [$this->id]),
            'url_status_update' => route('general.bank.update.status', [$this->id]),
        ];
    }
}
