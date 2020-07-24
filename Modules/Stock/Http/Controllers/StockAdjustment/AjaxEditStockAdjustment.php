<?php

namespace Modules\Stock\Http\Controllers\StockAdjustment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockAdjustment;
use Modules\Stock\Transformers\StockAdjustment\EditStockAdjustmentResource;

class AjaxEditStockAdjustment extends Controller
{
    public function __invoke(Request $request, $code)
    {
        //$code = 'X000';
        $stockAdjustment = StockAdjustment::where('code', $code)->get();
        return EditStockAdjustmentResource::collection($stockAdjustment);
    }
}
