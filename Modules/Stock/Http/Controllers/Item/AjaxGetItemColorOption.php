<?php

namespace Modules\Stock\Http\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Item;
use Modules\Stock\Transformers\Item\ItemColorOptionResource;

class AjaxGetItemColorOption extends Controller
{
    public function __invoke(Request $request)
    {
        $items = Item::query()
            ->where('color','<>','')
            ->orderBy('color', 'Asc')
            ->groupBy('color')
            ->get();

        return ItemColorOptionResource::collection($items);
    }
}
