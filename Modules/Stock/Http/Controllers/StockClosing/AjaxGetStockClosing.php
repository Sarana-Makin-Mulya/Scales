<?php

namespace Modules\Stock\Http\Controllers\StockClosing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockClosing;
use Modules\Stock\Transformers\StockClosing\StockClosingResource;

class AjaxGetStockClosing extends Controller
{
    public function __invoke(Request $request)
    {
        $items = StockClosing::query()
            ->where('status', StockClosing::ACTIVE)
            ->orderBy('issue_date', 'Desc')
            ->orderBy('id', 'Desc')
            ->paginate($request->per_page);

        return StockClosingResource::collection($items);
    }
}
