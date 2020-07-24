<?php

namespace Modules\Stock\Http\Controllers\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Stock\Entities\ItemCategory;
use Modules\Stock\Transformers\ItemCategoryResource;

class AjaxGetCategoryOptions extends Controller
{
    public function __invoke(Request $request)
    {
            $items = ItemCategory::query()
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->get();

        return ItemCategoryResource::collection($items);
    }
}
