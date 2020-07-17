<?php

namespace Modules\General\Transformers\Currency;

use Illuminate\Http\Resources\Json\Resource;

class CurrencyResource extends Resource
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
            'symbol' => $this->symbol,
            'status' => (boolean) $this->is_active,
            'url_edit' => route('general.currency.update', [$this->id]),
            'url_delete' => route('ajax.general.destroy.currency', [$this->id]),
            'url_status_update' => route('general.currency.update.status', [$this->id]),
        ];
    }
}
