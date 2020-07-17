<?php

namespace Modules\General\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockTransaction;
use Modules\Stock\Transformers\Stock\StockHistoryResource;

class AjaxGetDashboardHistoryStock extends Controller
{
    public function __invoke(Request $request)
    {
        $data  = StockTransaction::query()
            ->whereYear('transaction_date', substr(now(),0,4))
            ->whereMonth('transaction_date', substr(now(),5,2))
            ->where('entry_status', '<>', StockTransaction::STOCK_SUMMARY)
            ->orderBy('transaction_date', 'Desc')
            ->skip(0)
            ->take(7)
            ->get();
        return StockHistoryResource::collection($data);
    }
}
