<?php

namespace Modules\General\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\PurchaseOrder\Entities\PurchaseOrder;
use Modules\PurchaseOrder\Transformers\PurchaseOrder\PurchaseOrderResource;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Transformers\Stock\StockHistoryResource;

class AjaxGetDashboardHistoryPurchaseOrder extends Controller
{
    public function __invoke(Request $request)
    {
        $data  = PurchaseOrder::query()
            ->whereYear('issue_date', substr(now(),0,4))
            ->whereMonth('issue_date', substr(now(),5,2))
            ->orderBy('issue_date', 'Desc')
            ->skip(0)
            ->take(7)
            ->get();
        return PurchaseOrderResource::collection($data);
    }
}
