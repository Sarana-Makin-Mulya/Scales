<?php

namespace Modules\Stock\Http\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockOldApp;
use Modules\Stock\Transformers\Item\DetailItemStockOldAppResource;

class AjaxGetDetailItemStockOldApp extends Controller
{
    public function __invoke(Request $request, $id)
    {
        $items = StockOldApp::query()
            ->where('id', $id)
            ->first();

        if (!empty($items)) {
            return New DetailItemStockOldAppResource($items);
        } else {
            return "404";
        }
    }
}
