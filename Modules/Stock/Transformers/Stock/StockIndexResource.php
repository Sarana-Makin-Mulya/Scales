<?php

namespace Modules\Stock\Transformers\Stock;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\DB;
use Modules\Stock\Entities\ItemUnitConversion;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Transformers\ItemCategoryResource;

class StockIndexResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $unitPrimary = ItemUnitConversion::where('item_code',$this->code)->where('is_active',1)->where('is_primary',1)->first();
        $unit_id = (!empty($unitPrimary)) ? $unitPrimary->unit_id : '';
        $unit_name = (!empty($unitPrimary)) ? getUnitName($unitPrimary->unit_id) : '';

        return [
            'code' => $this->code,
            'item_category' =>  getCategoryName($this->item_category_id),
            'name' => $this->name,
            'detail' => $this->detail,
            'max_stock' => $this->max_stock,
            'min_stock' => $this->min_stock,
            'stock_min' => $this->min_stock,
            'stock_first' => getItemStockTransaction($this->code,0),
            'stock_in' => getItemStockTransaction($this->code,1),
            'stock_out' => getItemStockTransaction($this->code,2),
            'stock_last' => getItemStock($this->code),
            'quarantine' => getItemStockTransaction($this->code,3),
            'borrowed' => getItemStockBorrowed($this->code),
            'status' => (boolean) $this->is_active,
            'unit_id' => $unit_id,
            'unit_name' => $unit_name,
        ];
    }

}
