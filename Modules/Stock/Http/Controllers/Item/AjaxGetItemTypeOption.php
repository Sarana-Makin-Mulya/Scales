<?php

namespace Modules\Stock\Http\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Item;
use Modules\Stock\Transformers\Item\ItemTypeOptionResource;

class AjaxGetItemTypeOption extends Controller
{
    public function __invoke(Request $request)
    {
        $items = Item::query()
            ->where('type','<>','')
            ->orderBy('type', 'Asc')
            ->groupBy('type')
            ->get();

        return ItemTypeOptionResource::collection($items);
    }
}
