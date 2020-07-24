<?php

namespace Modules\Stock\Http\Controllers\Item;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\Item;
use Modules\Stock\Transformers\FindItemInformationResource;

class AjaxGetFindItemInformation extends Controller
{
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request)
    {
        $param = $request->keyword;
        $items = Item::query()
            ->where('code', 'LIKE', '%' . $param . '%')
            ->orWhere('detail', 'LIKE', '%' . $param . '%')
            ->take(15)
            ->get();

        return FindItemInformationResource::collection($items);
    }
}
