<?php

namespace Modules\Stock\Http\Controllers\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AjaxGetItemStockValue extends Controller
{

    public function __invoke(Request $request)
    {
        if (!empty($request->item_code)) {
            return ['stock' => getItemStock($request->item_code)];
        } else {
            return ['stock' => 0];
        }
    }
}
