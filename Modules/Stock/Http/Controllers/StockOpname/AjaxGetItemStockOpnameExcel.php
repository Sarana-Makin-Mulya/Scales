<?php

namespace Modules\Stock\Http\Controllers\StockOpname;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Item;
use Modules\Stock\Transformers\StockOpname\ItemStockOpnameExcelResource;

class AjaxGetItemStockOpnameExcel extends Controller
{
    public function __invoke(Request $request)
    {
        $item = Item::all()->random(10);
        return ItemStockOpnameExcelResource::collection($item);
    }
}
