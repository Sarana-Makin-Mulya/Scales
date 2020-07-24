<?php

namespace Modules\Stock\Http\Controllers\StockAdjustment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockAdjustment;
use Modules\Stock\Entities\StockAdjustmentItem;

class AjaxGetRowStockAdjustmentApprovalsPendingPerItem extends Controller
{
    public function __invoke(Request $request)
    {
        return  StockAdjustmentItem::where('approvals_status', StockAdjustmentItem::APPROVALS_PENDING)->get()->count();
    }
}
