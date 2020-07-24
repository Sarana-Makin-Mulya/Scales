<?php

namespace Modules\Stock\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class ItemCategoryResource extends Resource
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
            'code' => $this->code,
            'old_code' => $this->old_code,
            'name' => $this->name,
            'slug' => $this->slug,
            'status' => (boolean) $this->is_active,
            'url_edit' => route('stock.item.category.update', [$this->id]),
            'url_status_update' => route('stock.item.category.update.status', [$this->id]),
            'url_delete' => route('ajax.stock.item.destroy.category', [$this->id]),
        ];
    }
}
