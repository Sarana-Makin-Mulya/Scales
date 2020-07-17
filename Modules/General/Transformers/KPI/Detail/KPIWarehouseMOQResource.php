<?php

namespace Modules\General\Transformers\KPI\Detail;

use Illuminate\Http\Resources\Json\Resource;

class KPIWarehouseMOQResource extends Resource
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
            'detail' => $this->detail,
            'min_stock' => $this->min_stock,
            'current_stock' => $this->current_stock,
            'is_active' => $this->is_active
        ];
    }
}
