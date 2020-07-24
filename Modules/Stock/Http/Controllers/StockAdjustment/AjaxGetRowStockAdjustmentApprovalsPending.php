<?php

namespace Modules\Stock\Http\Controllers\StockAdjustment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockAdjustment;

class AjaxGetRowStockAdjustmentApprovalsPending extends Controller
{
    public function __invoke(Request $request)
    {
        return  StockAdjustment::where('status', StockAdjustment::REQUEST)->get()->count();
    }
}
