<?php

namespace Modules\Stock\Http\Controllers\StockOpname;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockOpnameGroup;
use Modules\Stock\Transformers\StockOpname\StockOpnameDailyResource;

class AjaxGetStockOpnameDaily extends Controller
{
    public function __invoke(Request $request)
    {
        $stockOpnameDaily = StockOpnameGroup::query()
            ->where('type', 'daily')
            ->orderBy('updated_at', 'Desc')
            ->paginate($request->per_page);

        return StockOpnameDailyResource::collection($stockOpnameDaily);
    }
}
