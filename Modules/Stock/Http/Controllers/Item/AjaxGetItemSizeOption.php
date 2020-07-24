<?php

namespace Modules\Stock\Http\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Item;
use Modules\Stock\Transformers\Item\ItemSizeOptionResource;

class AjaxGetItemSizeOption extends Controller
{
    public function __invoke(Request $request)
    {
        $items = Item::query()
            ->where('size','<>','')
            ->orderBy('size', 'Asc')
            ->groupBy('size')
            ->get();

        return ItemSizeOptionResource::collection($items);
    }
}
