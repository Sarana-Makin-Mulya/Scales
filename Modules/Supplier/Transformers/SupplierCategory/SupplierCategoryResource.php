<?php

namespace Modules\Supplier\Transformers\SupplierCategory;

use Illuminate\Http\Resources\Json\Resource;

class SupplierCategoryResource extends Resource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'status' => (boolean) $this->is_active,
            'url_edit' => route('supplier.category.update', [$this->id]),
            'url_status_update' => route('supplier.category.update.status', [$this->id]),
            'url_delete' => route('ajax.destroy.supplier.category', [$this->id]),
        ];
    }
}
