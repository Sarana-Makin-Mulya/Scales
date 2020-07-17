<?php

namespace Modules\PublicWarehouse\Transformers\Weighing;

use Illuminate\Http\Resources\Json\Resource;

class WeighingItemResource extends Resource
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
            'code' => $this->code,
            'name' => $this->name,
            'description' =>$this->description,
            'url_edit' => route('wh.weighing.item.update', [$this->code]),
            'url_delete' => route('ajax.wh.destroy.weighing.item', [$this->code]),
        ];
    }
}
