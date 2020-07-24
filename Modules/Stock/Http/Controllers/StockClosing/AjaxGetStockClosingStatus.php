<?php

namespace Modules\Stock\Http\Controllers\StockClosing;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\StockClosing;
use Modules\Stock\Transformers\StockClosing\StockClosingResource;

class AjaxGetStockClosingStatus extends Controller
{
    public function __invoke(Request $request)
    {
        return getClosingStockStatus();
    }
}
