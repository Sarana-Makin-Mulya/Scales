<?php

namespace Modules\Stock\Http\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Item;
use Modules\Stock\Transformers\FindItemResource;

class AjaxGetItemOptions extends Controller
{
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $param  = $request->keyword;
        $source = $request->source;

        $query  = Item::query();

        if (!empty($source)) {
            if ($source==1) {
                $query = $query->where('current_stock', '>', 0);
            }
        }

        $items = $query
            ->orderBy('code', 'ASC')
            ->take(15)
            ->get();

        return FindItemResource::collection($items);
    }
}
