<?php

namespace Modules\Stock\Transformers\StockAdjustment;

use Illuminate\Http\Resources\Json\Resource;

class ApprovalsStockAdjustmentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
