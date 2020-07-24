<?php

namespace Modules\Stock\Http\Controllers\StockAdjustment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockAdjustmentCategory;
use Modules\Stock\Transformers\StockAdjustment\StockAdjustmentCategoryResource;

class AjaxGetStockAdjustmentCategory extends Controller
{

    public function __invoke(Request $request)
    {
        $query = StockAdjustmentCategory::query();
        if ($request->type==3) {
            $query = $query->where('stock', StockAdjustmentCategory::STOCK_IN);
        } elseif ($request->type==2) {
            $query = $query->where('stock', StockAdjustmentCategory::STOCK_OUT);
        } else {
            $query = $query;
        }

        $items = $query
            ->where('name', '<>', 'Dead Stock')
            ->orderBy('name', 'Desc')
            ->get();

        return StockAdjustmentCategoryResource::collection($items);
    }
}
