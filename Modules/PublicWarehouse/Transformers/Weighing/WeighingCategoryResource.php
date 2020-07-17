<?php

namespace Modules\PublicWarehouse\Transformers\Weighing;

use Illuminate\Http\Resources\Json\Resource;

class WeighingCategoryResource extends Resource
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
            'description' =>$this->description,
            'url_edit' => route('wh.weighing.category.update', [$this->id]),
            'url_delete' => route('ajax.wh.destroy.weighing.category', [$this->id]),
        ];
    }
}
