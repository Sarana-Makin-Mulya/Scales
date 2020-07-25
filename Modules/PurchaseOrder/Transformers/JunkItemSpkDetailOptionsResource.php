<?php

namespace Modules\PurchaseOrder\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class JunkItemSpkDetailOptionsResource extends Resource
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
            'junk_item_spk_code' => $this->junk_item_spk_code,
            'junk_item_code' => $this->junk_item_code,
            'detail' => getJunkItemName($this->junk_item_code),
        ];
    }
}
