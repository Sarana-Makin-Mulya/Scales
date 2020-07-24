<?php

namespace Modules\Stock\Transformers\TransactionCategory;

use Illuminate\Http\Resources\Json\Resource;

class StockTransactionCategoryResource extends Resource
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
            'status' => (boolean) $this->is_active,
            'url_edit' => route('stock.transaction.category.update', [$this->id]),
            'url_status_update' => route('stock.transaction.category.update.status', [$this->id]),
            'url_delete' => route('ajax.stock.destroy.transaction.category', [$this->id]),
        ];
    }
}
