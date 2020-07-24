<?php

namespace Modules\Stock\Http\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Item;
use Modules\Stock\Transformers\Item\DetailItemResource;

class AjaxGetDetailItem extends Controller
{
    public function __invoke(Request $request, $code)
    {
        $items = Item::query()
            ->where('code', $code)
            ->first();

        if (!empty($items)) {
            return New DetailItemResource($items);
        } else {
            return "404";
        }
    }
}
