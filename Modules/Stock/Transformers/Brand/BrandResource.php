<?php

namespace Modules\Stock\Transformers\Brand;

use Illuminate\Http\Resources\Json\Resource;

class BrandResource extends Resource
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
            'slug' => $this->slug,
            'status' => (boolean) $this->is_active,
            'url_edit' => route('stock.item.brand.update', [$this->id]),
            'url_delete' => route('ajax.stock.item.destroy.brand', [$this->id]),
            'url_status_update' => route('stock.item.brand.update.status', [$this->id]),
        ];
    }
}
